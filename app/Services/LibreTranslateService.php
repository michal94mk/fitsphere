<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

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
     * @return string|null Translated text or null in case of error
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
        
        try {
            $response = Http::timeout(10)->post($this->apiUrl . '/translate', [
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
            
            return null;
        } catch (\Exception $e) {
            Log::error('LibreTranslate error', [
                'message' => $e->getMessage(),
                'text_length' => strlen($text)
            ]);
            
            return null;
        }
    }
    
    /**
     * Detects the language of a text
     * 
     * @param string $text Text to analyze
     * @return string|null Language code or null in case of error
     */
    public function detectLanguage($text)
    {
        if (empty($text)) {
            return null;
        }
        
        try {
            $response = Http::timeout(5)->post($this->apiUrl . '/detect', [
                'q' => substr($text, 0, 500), // Limit to 500 characters for efficiency
                'api_key' => $this->apiKey,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[0]['language'])) {
                    return $data[0]['language'];
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('LibreTranslate language detection error', [
                'message' => $e->getMessage()
            ]);
            
            return null;
        }
    }
    
    /**
     * Get available languages from API
     * 
     * @return array List of available languages
     */
    public function getLanguages()
    {
        $cacheKey = 'libretranslate_languages';
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        try {
            $response = Http::timeout(5)->get($this->apiUrl . '/languages', [
                'api_key' => $this->apiKey,
            ]);
            
            if ($response->successful()) {
                $languages = $response->json();
                Cache::put($cacheKey, $languages, 60 * 60 * 24); // Cache for 24h
                return $languages;
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error('LibreTranslate error getting languages', [
                'message' => $e->getMessage()
            ]);
            
            return [];
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
        
        foreach ($texts as $index => $text) {
            $results[$index] = $this->translate($text, $source, $target);
        }
        
        return $results;
    }
} 