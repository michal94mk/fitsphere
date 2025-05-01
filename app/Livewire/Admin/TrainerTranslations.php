<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use App\Models\TrainerTranslation;
use Livewire\Component;
use Livewire\Attributes\Layout;

class TrainerTranslations extends Component
{
    public $trainerId;
    public $trainer;
    
    // Translation fields
    public $locale = 'pl'; // Default to Polish for new translations
    public $specialization;
    public $description;
    public $bio;
    public $specialties;
    
    // Existing translations list
    public $translations = [];
    public $editingTranslationId = null;
    
    /**
     * Mount the component
     */
    public function mount($id)
    {
        $this->trainerId = $id;
        $this->trainer = Trainer::findOrFail($id);
        $this->loadTranslations();
    }
    
    /**
     * Load translations for this trainer
     */
    public function loadTranslations()
    {
        $this->translations = $this->trainer->translations()->get()->toArray();
    }
    
    /**
     * Reset form fields
     */
    public function resetFormFields()
    {
        $this->editingTranslationId = null;
        $this->locale = 'pl';
        $this->specialization = '';
        $this->description = '';
        $this->bio = '';
        $this->specialties = '';
    }
    
    /**
     * Start editing an existing translation
     */
    public function editTranslation($translationId)
    {
        $translation = TrainerTranslation::findOrFail($translationId);
        
        $this->editingTranslationId = $translation->id;
        $this->locale = $translation->locale;
        $this->specialization = $translation->specialization;
        $this->description = $translation->description;
        $this->bio = $translation->bio;
        $this->specialties = $translation->specialties;
    }
    
    /**
     * Delete a translation
     */
    public function deleteTranslation($translationId)
    {
        $translation = TrainerTranslation::findOrFail($translationId);
        $translation->delete();
        
        $this->loadTranslations();
        session()->flash('success', 'Translation has been deleted.');
    }
    
    /**
     * Save translation
     */
    public function saveTranslation()
    {
        $this->validate([
            'locale' => 'required|in:en,pl',
            'specialization' => 'nullable|max:255',
            'description' => 'nullable',
            'bio' => 'nullable',
            'specialties' => 'nullable|max:255',
        ]);
        
        if ($this->editingTranslationId) {
            // Update existing translation
            $translation = TrainerTranslation::findOrFail($this->editingTranslationId);
        } else {
            // Check if translation for this locale already exists
            $exists = $this->trainer->translations()->where('locale', $this->locale)->exists();
            if ($exists) {
                session()->flash('error', 'Translation for the selected language already exists.');
                return;
            }
            
            // Create a new translation
            $translation = new TrainerTranslation();
            $translation->trainer_id = $this->trainerId;
            $translation->locale = $this->locale;
        }
        
        // Update translation data
        $translation->specialization = $this->specialization;
        $translation->description = $this->description;
        $translation->bio = $this->bio;
        $translation->specialties = $this->specialties;
        $translation->save();
        
        $this->loadTranslations();
        $this->resetFormFields();
        
        session()->flash('success', 'Translation has been saved.');
    }
    
    /**
     * Cancel editing
     */
    public function cancelEdit()
    {
        $this->resetFormFields();
    }
    
    /**
     * Render the component
     */
    #[Layout('layouts.admin', ['header' => 'Manage Trainer Translations'])]
    public function render()
    {
        return view('livewire.admin.trainer-translations', [
            'trainer' => $this->trainer
        ]);
    }
} 