<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CategoryTranslations extends Component
{
    public $categoryId;
    public $category;
    
    // Translation fields
    public $locale = 'pl'; // Default to Polish for new translations
    public $name;
    public $description;
    
    // Existing translations list
    public $translations = [];
    public $editingTranslationId = null;
    
    /**
     * Mount the component
     */
    public function mount($id)
    {
        $this->categoryId = $id;
        $this->category = Category::findOrFail($id);
        $this->loadTranslations();
    }
    
    /**
     * Load translations for this category
     */
    public function loadTranslations()
    {
        $this->translations = $this->category->translations()->get()->toArray();
    }
    
    /**
     * Reset form fields
     */
    public function resetFormFields()
    {
        $this->editingTranslationId = null;
        $this->locale = 'pl';
        $this->name = '';
        $this->description = '';
    }
    
    /**
     * Start editing an existing translation
     */
    public function editTranslation($translationId)
    {
        $translation = CategoryTranslation::findOrFail($translationId);
        
        $this->editingTranslationId = $translation->id;
        $this->locale = $translation->locale;
        $this->name = $translation->name;
        $this->description = $translation->description;
    }
    
    /**
     * Delete a translation
     */
    public function deleteTranslation($translationId)
    {
        $translation = CategoryTranslation::findOrFail($translationId);
        $translation->delete();
        
        $this->loadTranslations();
        session()->flash('success', 'Tłumaczenie zostało usunięte.');
    }
    
    /**
     * Save translation
     */
    public function saveTranslation()
    {
        $this->validate([
            'locale' => 'required|in:en,pl',
            'name' => 'required|min:3',
        ]);
        
        // Generate slug from name
        $slug = Str::slug($this->name);
        
        if ($this->editingTranslationId) {
            // Update existing translation
            $translation = CategoryTranslation::findOrFail($this->editingTranslationId);
        } else {
            // Check if translation for this locale already exists
            $exists = $this->category->translations()->where('locale', $this->locale)->exists();
            if ($exists) {
                session()->flash('error', 'Tłumaczenie dla wybranego języka już istnieje.');
                return;
            }
            
            // Create a new translation
            $translation = new CategoryTranslation();
            $translation->category_id = $this->categoryId;
            $translation->locale = $this->locale;
        }
        
        // Update translation data
        $translation->name = $this->name;
        $translation->slug = $slug;
        $translation->description = $this->description;
        $translation->save();
        
        $this->loadTranslations();
        $this->resetFormFields();
        
        session()->flash('success', 'Tłumaczenie zostało zapisane.');
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
    #[Layout('layouts.admin', ['header' => 'Zarządzaj tłumaczeniami kategorii'])]
    public function render()
    {
        return view('livewire.admin.category-translations', [
            'category' => $this->category
        ]);
    }
} 