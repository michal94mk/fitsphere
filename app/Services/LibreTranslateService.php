<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ApiException;
use App\Services\ApiRetryService;
use Throwable;

// Service for handling translations via LibreTranslate API
class LibreTranslateService
{
    protected $apiUrl;
    protected $apiKey;
    protected $apiRetryService;
    
    public function __construct(?ApiRetryService $apiRetryService = null)
    {
        $this->apiUrl = env('LIBRETRANSLATE_URL', 'https://translate.argosopentech.com');
        $this->apiKey = env('LIBRETRANSLATE_API_KEY', '');
        $this->apiRetryService = $apiRetryService ?? app(ApiRetryService::class);
    }
    
    // Translate text between languages
    public function translate(string $text, string $source, string $target, string $format = 'text'): string
    {
        if (empty($text)) {
            return '';
        }
        
        // Check if the text contains HTML tags
        if ($format === 'text' && preg_match('/<[^>]+>/', $text)) {
            $format = 'html';
        }
        
        // Check in cache if translation already exists
        $cacheKey = 'translation_' . md5($text . $source . $target . $format);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $endpoint = '/translate';
        
        return $this->apiRetryService->executeWithRetry(
            function() use ($text, $source, $target, $format, $endpoint, $cacheKey) {
                $response = Http::timeout(10)->post($this->apiUrl . $endpoint, [
                    'q' => $text,
                    'source' => $source,
                    'target' => $target,
                    'format' => $format,
                    'api_key' => $this->apiKey,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['translatedText'])) {
                        // Store translation in cache for 24 hours
                        Cache::put($cacheKey, $data['translatedText'], 60 * 60 * 24);
                        return $data['translatedText'];
                    }
                }
                
                Log::warning('LibreTranslate translation failed', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'text_length' => strlen($text)
                ]);
                
                throw new ApiException(
                    $endpoint,
                    'Translation failed: ' . ($response->json()['error'] ?? 'Unknown error'),
                    'LibreTranslate',
                    $response->status()
                );
            },
            'LibreTranslate',
            $endpoint
        );
    }
    
    // Detect the language of a text
    public function detectLanguage(string $text): ?string
    {
        if (empty($text)) {
            return null;
        }
        
        $endpoint = '/detect';
        
        return $this->apiRetryService->executeWithRetry(
            function() use ($text, $endpoint) {
                $response = Http::timeout(5)->post($this->apiUrl . $endpoint, [
                    'q' => substr($text, 0, 500), // Limit to 500 characters for efficiency
                    'api_key' => $this->apiKey,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data[0]['language'])) {
                        return $data[0]['language'];
                    }
                }
                
                throw new ApiException(
                    $endpoint,
                    'Language detection failed: ' . ($response->json()['error'] ?? 'Unknown error'),
                    'LibreTranslate',
                    $response->status()
                );
            },
            'LibreTranslate',
            $endpoint
        );
    }
    
    // Get available languages from API
    public function getLanguages(): array
    {
        $cacheKey = 'libretranslate_languages';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $endpoint = '/languages';
        
        return $this->apiRetryService->executeWithRetry(
            function() use ($endpoint, $cacheKey) {
                $response = Http::timeout(5)->get($this->apiUrl . $endpoint, [
                    'api_key' => $this->apiKey,
                ]);
                
                if ($response->successful()) {
                    $languages = $response->json();
                    Cache::put($cacheKey, $languages, 60 * 60 * 24); // Cache for 24h
                    return $languages;
                }
                
                throw new ApiException(
                    $endpoint,
                    'Failed to retrieve languages: ' . ($response->json()['error'] ?? 'Unknown error'),
                    'LibreTranslate',
                    $response->status()
                );
            },
            'LibreTranslate',
            $endpoint
        );
    }
    
    // Translate multiple text fragments simultaneously
    public function batchTranslate(array $texts, string $source, string $target): array
    {
        $results = [];
        $errors = [];
        
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
} 