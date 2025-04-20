<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;

/**
 * Language switcher component for multilingual application.
 * 
 * Responsible for rendering the language selection interface
 * and tracking the currently selected application language.
 * Uses wire:navigate for seamless switching without full page refresh.
 */
class LanguageSwitcher extends Component
{
    /**
     * Stores the currently selected application language.
     * 
     * Used to highlight the active language in the interface.
     * 
     * @var string
     */
    public string $currentLocale;

    /**
     * Initializes the component with the currently selected language.
     * 
     * Retrieves the current application language and stores it in
     * the component variable so the view can properly mark
     * the active language.
     * 
     * @return void
     */
    public function mount()
    {
        $this->currentLocale = App::getLocale();
    }

    /**
     * Renders the language switcher view.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.language-switcher');
    }
} 