<?php

namespace App\Services;

use Illuminate\Http\Request;

class LanguageService
{
    public static function switchLanguage(Request $request)
    {
        // Validate the locale
        $request->validate([
            'locale' => 'required|in:en,pl'
        ]);
        
        // Store the locale in session
        session()->put('locale', $request->locale);
        
        // Get previous URL to redirect back
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        
        // Start building the new URL
        $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        if (isset($parsedUrl['port'])) {
            $newUrl .= ':' . $parsedUrl['port'];
        }
        $newUrl .= $parsedUrl['path'] ?? '';
        
        // Parse the query parameters
        $queryParams = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }
        
        // Update or add the locale parameter
        $queryParams['locale'] = $request->locale;
        
        // Build the new query string
        $newQueryString = http_build_query($queryParams);
        if (!empty($newQueryString)) {
            $newUrl .= '?' . $newQueryString;
        }
        
        // Add fragment if it exists
        if (isset($parsedUrl['fragment'])) {
            $newUrl .= '#' . $parsedUrl['fragment'];
        }
        
        return $newUrl;
    }
} 