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
     * Tłumaczy tekst pomiędzy dwoma językami
     * 
     * @param string $text Tekst do przetłumaczenia
     * @param string $source Kod języka źródłowego (np. 'en')
     * @param string $target Kod języka docelowego (np. 'pl')
     * @param string $format Format tekstu ('text' lub 'html')
     * @return string|null Przetłumaczony tekst lub null w przypadku błędu
     */
    public function translate($text, $source, $target, $format = 'text')
    {
        if (empty($text)) {
            return $text;
        }
        
        // Sprawdź czy tekst zawiera tagi HTML
        if ($format === 'text' && preg_match('/<[^>]+>/', $text)) {
            $format = 'html';
        }
        
        // Sprawdź w cache czy tłumaczenie już istnieje
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
                    // Zachowaj tłumaczenie w cache na 24 godziny
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
     * Wykrywa język tekstu
     * 
     * @param string $text Tekst do analizy
     * @return string|null Kod języka lub null w przypadku błędu
     */
    public function detectLanguage($text)
    {
        if (empty($text)) {
            return null;
        }
        
        try {
            $response = Http::timeout(5)->post($this->apiUrl . '/detect', [
                'q' => substr($text, 0, 500), // Ogranicz do 500 znaków dla oszczędności
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
     * Pobiera dostępne języki z API
     * 
     * @return array Lista dostępnych języków
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
                Cache::put($cacheKey, $languages, 60 * 60 * 24); // Cache na 24h
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
     * Tłumaczy wiele fragmentów tekstu jednocześnie
     * 
     * @param array $texts Tablica z tekstami do tłumaczenia
     * @param string $source Kod języka źródłowego
     * @param string $target Kod języka docelowego
     * @return array Tablica z przetłumaczonymi tekstami
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