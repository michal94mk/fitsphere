<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image asset URL with fallback to placeholder
     */
    public static function getImageAsset(?string $imagePath, ?string $fallback = null): string
    {
        // If no image path provided, use fallback or placeholder
        if (!$imagePath) {
            return $fallback ?? self::getPlaceholderUrl();
        }

        // Check if image exists in storage
        if (Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        // Return fallback or placeholder if image doesn't exist
        return $fallback ?? self::getPlaceholderUrl();
    }

    /**
     * Get placeholder image URL
     */
    public static function getPlaceholderUrl(int $width = 800, int $height = 600, string $text = 'FitSphere'): string
    {
        // Use a reliable placeholder service
        return "https://via.placeholder.com/{$width}x{$height}/4F46E5/FFFFFF?text=" . urlencode($text);
    }

    /**
     * Get user avatar with fallback
     */
    public static function getUserAvatar(?string $imagePath, string $userName): string
    {
        if (!$imagePath) {
            return "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=4F46E5&color=FFFFFF&size=200";
        }

        if (Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        return "https://ui-avatars.com/api/?name=" . urlencode($userName) . "&background=4F46E5&color=FFFFFF&size=200";
    }
}
