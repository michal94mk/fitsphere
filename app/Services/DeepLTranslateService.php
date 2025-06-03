<?php

namespace App\Services;

use DeepL\Translator;
use DeepL\TextResult;
use DeepL\DeepLException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ApiException;
use App\Services\ApiRetryService;
use GuzzleHttp\Client as HttpClient;
use Throwable;

// Service for handling translations via DeepL API
class DeepLTranslateService
{
    protected $translator;
    protected $apiRetryService;
    
    public function __construct(?ApiRetryService $apiRetryService = null)
    {
        $authKey = config('services.deepl.key');
        if (empty($authKey)) {
            throw new \InvalidArgumentException('DeepL API key is required. Please set DEEPL_API_KEY in your environment.');
        }
        
        // Determine if we should use the free or pro API URL
        $serverUrl = config('services.deepl.free_api', false) ? 'https://api-free.deepl.com' : null;
        
        $options = [
            'timeout' => 10.0, // 10 second timeout
            'connect_timeout' => 5.0, // 5 second connection timeout
        ];
        
        if ($serverUrl) {
            $options['server_url'] = $serverUrl;
        }
        
        // For local environment, create custom HTTP client with disabled SSL verification
        if (app()->environment('local')) {
            $httpClient = new HttpClient([
                'verify' => false,
                'timeout' => 10.0,
                'connect_timeout' => 5.0,
            ]);
            
            $options['http_client'] = $httpClient;
            
            Log::info('DeepL SSL verification disabled for local environment');
        }
        
        try {
            $this->translator = new Translator($authKey, $options);
        } catch (\Exception $e) {
            Log::error('Failed to initialize DeepL translator', [
                'error' => $e->getMessage(),
                'server_url' => $serverUrl
            ]);
            throw new \InvalidArgumentException('Failed to initialize DeepL translator: ' . $e->getMessage());
        }
        
        $this->apiRetryService = $apiRetryService ?? app(ApiRetryService::class);
    }
    
    // Translate text between languages
    public function translate(string $text, string $source, string $target, string $format = 'text'): string
    {
        if (empty($text)) {
            return '';
        }
        
        // DeepL uses null for auto-detection, not 'auto'
        if ($source === 'auto' || $source === 'AUTO') {
            $source = null;
        }
        
        // Check in cache if translation already exists
        $cacheKey = 'translation_' . md5($text . ($source ?? 'auto') . $target . $format);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        return $this->apiRetryService->executeWithRetry(
            function() use ($text, $source, $target, $format, $cacheKey) {
                try {
                    $options = [];
                    
                    // Handle HTML format
                    if ($format === 'html') {
                        $options['tag_handling'] = 'html';
                    }
                    
                    // Set timeout for this specific request
                    $options['timeout'] = 8.0; // 8 seconds for individual requests
                    
                    // Log the translation attempt
                    Log::info('Attempting DeepL translation', [
                        'text_length' => strlen($text),
                        'source' => $source,
                        'target' => $target,
                        'format' => $format
                    ]);
                    
                    // Translate text
                    $result = $this->translator->translateText($text, $source, $target, $options);
                    
                    // Handle both single result and array of results
                    $translatedText = $result instanceof TextResult ? $result->text : $result[0]->text;
                    
                    // Store translation in cache for 24 hours
                    Cache::put($cacheKey, $translatedText, 60 * 60 * 24);
                    
                    Log::info('DeepL translation successful', [
                        'text_length' => strlen($text),
                        'translated_length' => strlen($translatedText)
                    ]);
                    
                    return $translatedText;
                    
                } catch (DeepLException $e) {
                    Log::warning('DeepL translation failed', [
                        'error' => $e->getMessage(),
                        'text_length' => strlen($text),
                        'source' => $source,
                        'target' => $target,
                        'code' => $e->getCode()
                    ]);
                    
                    throw new ApiException(
                        '/translate',
                        'Translation failed: ' . $e->getMessage(),
                        'DeepL',
                        $e->getCode()
                    );
                } catch (\Exception $e) {
                    Log::error('Unexpected error during DeepL translation', [
                        'error' => $e->getMessage(),
                        'text_length' => strlen($text),
                        'source' => $source,
                        'target' => $target
                    ]);
                    
                    throw new ApiException(
                        '/translate',
                        'Unexpected translation error: ' . $e->getMessage(),
                        'DeepL',
                        500
                    );
                }
            },
            'DeepL',
            '/translate',
            2, // Only 2 retry attempts to avoid long delays
            [60, 408, 429, 500, 502, 503, 504], // Status codes to retry (added 60 for SSL errors)
            1000, // 1 second initial delay
            1.5 // Lower backoff factor
        );
    }
    
    // Detect the language of a text
    public function detectLanguage(string $text): ?string
    {
        if (empty($text)) {
            return null;
        }
        
        return $this->apiRetryService->executeWithRetry(
            function() use ($text) {
                try {
                    // DeepL automatically detects language when translating with null source
                    // For detection only, we can translate to a common language and check detected source
                    $result = $this->translator->translateText(
                        substr($text, 0, 500), // Limit to 500 characters for efficiency
                        null, // Auto-detect source
                        'en' // Translate to English for detection
                    );
                    
                    return $result->detectedSourceLang;
                    
                } catch (DeepLException $e) {
                    throw new ApiException(
                        '/detect',
                        'Language detection failed: ' . $e->getMessage(),
                        'DeepL',
                        $e->getCode()
                    );
                }
            },
            'DeepL',
            '/detect'
        );
    }
    
    // Get available languages from API
    public function getLanguages(): array
    {
        $cacheKey = 'deepl_languages';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        return $this->apiRetryService->executeWithRetry(
            function() use ($cacheKey) {
                try {
                    $sourceLanguages = $this->translator->getSourceLanguages();
                    $targetLanguages = $this->translator->getTargetLanguages();
                    
                    $languages = [
                        'source' => array_map(function($lang) {
                            return [
                                'code' => $lang->code,
                                'name' => $lang->name
                            ];
                        }, $sourceLanguages),
                        'target' => array_map(function($lang) {
                            return [
                                'code' => $lang->code,
                                'name' => $lang->name,
                                'supports_formality' => $lang->supportsFormality ?? false
                            ];
                        }, $targetLanguages)
                    ];
                    
                    Cache::put($cacheKey, $languages, 60 * 60 * 24); // Cache for 24h
                    return $languages;
                    
                } catch (DeepLException $e) {
                    throw new ApiException(
                        '/languages',
                        'Failed to retrieve languages: ' . $e->getMessage(),
                        'DeepL',
                        $e->getCode()
                    );
                }
            },
            'DeepL',
            '/languages'
        );
    }
    
    // Translate multiple text fragments simultaneously
    public function batchTranslate(array $texts, string $source, string $target): array
    {
        $results = [];
        $errors = [];
        
        // DeepL uses null for auto-detection
        if ($source === 'auto' || $source === 'AUTO') {
            $source = null;
        }
        
        foreach ($texts as $index => $text) {
            try {
                $results[$index] = $this->translate($text, $source, $target);
            } catch (ApiException $e) {
                Log::warning('Batch translation error for item ' . $index, [
                    'message' => $e->getMessage(),
                    'service' => $e->getServiceName(),
                    'text_length' => strlen($text)
                ]);
                
                $results[$index] = $text; // Fallback to original text
                $errors[$index] = $e->getMessage();
            }
        }
        
        if (!empty($errors)) {
            Log::warning('Some translations in batch failed', [
                'error_count' => count($errors),
                'total_count' => count($texts)
            ]);
        }
        
        return $results;
    }
    
    // Get usage information
    public function getUsage(): array
    {
        try {
            $usage = $this->translator->getUsage();
            
            return [
                'character_count' => $usage->character->count ?? 0,
                'character_limit' => $usage->character->limit ?? 0,
                'document_count' => $usage->document->count ?? 0,
                'document_limit' => $usage->document->limit ?? 0,
            ];
        } catch (DeepLException $e) {
            Log::warning('DeepL usage check failed', [
                'error' => $e->getMessage()
            ]);
            
            throw new ApiException(
                '/usage',
                'Failed to get usage information: ' . $e->getMessage(),
                'DeepL',
                $e->getCode()
            );
        }
    }
} 