<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ApiException;
use Throwable;

class LibreTranslateService
{
    protected $apiUrl;
    protected $apiKey;
    
    public function __construct()
    {
        $this->apiUrl = config('services.libretranslate.url', 'https://libretranslate.com');
        $this->apiKey = config('services.libretranslate.key');
    }
    
    /**
     * Translates text between two languages
     * 
     * @param string $text Text to translate
     * @param string $source Source language code (e.g. 'en')
     * @param string $target Target language code (e.g. 'pl')
     * @param string $format Text format ('text' or 'html')
     * @return string Translated text
     * @throws ApiException If translation fails
     */
    public function translate($text, $source, $target, $format = 'text')
    {
        if (empty($text)) {
            return $text;
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
        
        try {
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
        } catch (Throwable $e) {
            if ($e instanceof ApiException) {
                throw $e;
            }
            
            Log::error('LibreTranslate error', [
                'message' => $e->getMessage(),
                'text_length' => strlen($text)
            ]);
            
            throw new ApiException(
                $endpoint,
                'Translation service error: ' . $e->getMessage(),
                'LibreTranslate'
            );
        }
    }
    
    /**
     * Detects the language of a text
     * 
     * @param string $text Text to analyze
     * @return string Language code
     * @throws ApiException If language detection fails
     */
    public function detectLanguage($text)
    {
        if (empty($text)) {
            return null;
        }
        
        $endpoint = '/detect';
        
        try {
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
        } catch (Throwable $e) {
            if ($e instanceof ApiException) {
                throw $e;
            }
            
            Log::error('LibreTranslate language detection error', [
                'message' => $e->getMessage()
            ]);
            
            throw new ApiException(
                $endpoint,
                'Language detection error: ' . $e->getMessage(),
                'LibreTranslate'
            );
        }
    }
    
    /**
     * Get available languages from API
     * 
     * @return array List of available languages
     * @throws ApiException If retrieving languages fails
     */
    public function getLanguages()
    {
        $cacheKey = 'libretranslate_languages';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $endpoint = '/languages';
        
        try {
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
        } catch (Throwable $e) {
            if ($e instanceof ApiException) {
                throw $e;
            }
            
            Log::error('LibreTranslate error getting languages', [
                'message' => $e->getMessage()
            ]);
            
            throw new ApiException(
                $endpoint,
                'Error retrieving languages: ' . $e->getMessage(),
                'LibreTranslate'
            );
        }
    }
    
    /**
     * Translate multiple text fragments simultaneously
     * 
     * @param array $texts Array with texts to translate
     * @param string $source Source language code
     * @param string $target Target language code
     * @return array Array with translated texts
     */
    public function batchTranslate(array $texts, $source, $target)
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