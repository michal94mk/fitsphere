<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * Manages language switching functionality across the application
 * 
 * This component handles the interface for switching between available
 * languages and broadcasts changes to other components.
 */
class LanguageSwitcher extends Component
{
    /**
     * Switch the application language without page reload
     * 
     * Changes the application locale and notifies all listening components
     * about the language change through an event dispatcher.
     * 
     * @param string $locale Language code (en/pl)
     * @return void
     */
    public function switchLanguage($locale)
    {
        // Validate the locale is in our supported languages list
        if (in_array($locale, ['en', 'pl'])) {
            // Update PHP session and application locale
            Session::put('locale', $locale);
            App::setLocale($locale);
            
            // Notify all listening components about the language change
            // This triggers reactive updates without page reload
            $this->dispatch('language-changed', locale: $locale);
        }
    }

    /**
     * Get the currently active language code
     * 
     * @return string The current application locale
     */
    public function getCurrentLocale()
    {
        return App::getLocale();
    }

    /**
     * Render the language switcher component view
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.language-switcher');
    }
} 