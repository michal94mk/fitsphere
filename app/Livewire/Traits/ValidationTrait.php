<?php

namespace App\Livewire\Traits;

trait ValidationTrait
{
    protected function sanitizeInput(string $propertyName, $value = null): void
    {
        $value = $value ?? $this->{$propertyName};
        
        switch($propertyName) {
            case 'name':
            case 'first_name':
            case 'last_name':
                $this->{$propertyName} = $this->sanitizeName($value);
                break;
                
            case 'email':
                $this->{$propertyName} = $this->sanitizeEmail($value);
                break;
                
            case 'specialization':
            case 'title':
                $this->{$propertyName} = $this->sanitizeText($value);
                break;
                
            case 'description':
            case 'biography':
            case 'message':
            case 'content':
                $this->{$propertyName} = $this->sanitizeRichText($value);
                break;
                
            case 'phone':
                $this->{$propertyName} = $this->sanitizePhone($value);
                break;
                
            default:
                if (is_string($value)) {
                    $this->{$propertyName} = trim(strip_tags($value));
                }
                break;
        }
    }
    
    protected function sanitizeName(string $value): string
    {
        $cleaned = trim(strip_tags($value));
        // Remove multiple spaces
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return $cleaned;
    }
    
    protected function sanitizeEmail(string $value): string
    {
        return trim(strtolower(strip_tags($value)));
    }
    
    protected function sanitizeText(string $value): string
    {
        $cleaned = trim(strip_tags($value));
        // Remove excessive whitespace
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        return $cleaned;
    }
    
    protected function sanitizeRichText(string $value): string
    {
        $allowedTags = '<br><p><strong><em><u><ol><ul><li>';
        $cleaned = trim(strip_tags($value, $allowedTags));
        // Remove excessive whitespace but preserve line breaks
        $cleaned = preg_replace('/[ \t]+/', ' ', $cleaned);
        return $cleaned;
    }
    
    protected function sanitizePhone(string $value): string
    {
        // Remove everything except digits, spaces, +, -, ()
        $cleaned = preg_replace('/[^0-9\s\+\-\(\)]/', '', $value);
        return trim($cleaned);
    }
    
    protected function getNameValidationRules(int $minLength = 2, int $maxLength = 50): array
    {
        return [
            'required',
            'string',
            "min:{$minLength}",
            "max:{$maxLength}",
            'regex:/^[\pL\s\-\'\.À-ſ]+$/u',
        ];
    }
    
    protected function getEmailValidationRules(array $additionalRules = []): array
    {
        $rules = [
            'required',
            'string',
            'email:rfc,dns',
            'max:100',
            'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        ];
        
        return array_merge($rules, $additionalRules);
    }
    
    protected function getPasswordValidationRules(bool $requireConfirmation = true): array
    {
        $rules = [
            'required',
            'string',
            'min:8',
            'max:128',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ];
        
        if ($requireConfirmation) {
            $rules[] = 'confirmed';
        }
        
        return $rules;
    }
    
    protected function getPhotoValidationRules(int $maxSizeKB = 1024): array
    {
        return [
            'nullable',
            'image',
            "max:{$maxSizeKB}",
            'mimes:jpeg,jpg,png,webp',
            'dimensions:min_width=100,min_height=100,max_width=1500,max_height=1500',
        ];
    }
    
    protected function getTextValidationRules(int $minLength = 3, int $maxLength = 500, bool $nullable = false): array
    {
        $rules = [
            $nullable ? 'nullable' : 'required',
            'string',
            "min:{$minLength}",
            "max:{$maxLength}",
            'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ\r\n]*$/u',
        ];
        
        return array_filter($rules); // Remove null values
    }
    
    protected function getPostTitleValidationRules(): array
    {
        return [
            'required',
            'string',
            'min:3',
            'max:200',
            'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ]+$/u',
        ];
    }
    
    protected function getPostContentValidationRules(): array
    {
        return [
            'required',
            'string',
            'min:10',
            'max:15000',
            'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ\r\n]*$/u',
        ];
    }
    
    protected function getPostExcerptValidationRules(): array
    {
        return [
            'nullable',
            'string',
            'max:500',
            'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\À-ſ\r\n]*$/u',
        ];
    }
    
    protected function getCategoryNameValidationRules(array $additionalRules = []): array
    {
        $rules = [
            'required',
            'string',
            'min:2',
            'max:50',
            'regex:/^[\pL\pN\s\-\'\.\,\(\)\/\&À-ſ]+$/u',
        ];
        
        return array_merge($rules, $additionalRules);
    }
    
    protected function validateFieldRealTime(string $propertyName): void
    {
        // First sanitize
        $this->sanitizeInput($propertyName);
        
        // Clear previous errors for this field
        $this->resetErrorBag($propertyName);
        
        // Validate only this field
        $this->validateOnly($propertyName);
    }
    
    protected function getCommonValidationAttributes(): array
    {
        return [
            'name' => 'imię i nazwisko',
            'first_name' => 'imię',
            'last_name' => 'nazwisko',
            'email' => 'adres email',
            'password' => 'hasło',
            'password_confirmation' => 'potwierdzenie hasła',
            'phone' => 'telefon',
            'photo' => 'zdjęcie',
            'image' => 'obrazek',
            'description' => 'opis',
            'biography' => 'biografia',
            'specialization' => 'specjalizacja',
            'message' => 'wiadomość',
            'content' => 'treść',
            'title' => 'tytuł',
            'excerpt' => 'streszczenie',
            'category_name' => 'nazwa kategorii',
            'slug' => 'adres URL',
            'status' => 'status',
        ];
    }
} 