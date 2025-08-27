<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CategoryTranslations extends Component
{
    use HasFlashMessages;
    public $categoryId;
    public $category;
    
    public $locale = 'pl';
    public $name;
    
    public $translations = [];
    public $editingTranslationId = null;
    

    public function mount($id)
    {
        $this->categoryId = $id;
        $this->category = Category::findOrFail($id);
        $this->loadTranslations();
    }
    
    public function loadTranslations()
    {
        $this->translations = $this->category->translations()->get()->toArray();
    }
    
    public function resetFormFields()
    {
        $this->editingTranslationId = null;
        $this->locale = 'pl';
        $this->name = '';
    }
    
    public function editTranslation($translationId)
    {
        $translation = CategoryTranslation::findOrFail($translationId);
        
        $this->editingTranslationId = $translation->id;
        $this->locale = $translation->locale;
        $this->name = $translation->name;
    }
    
    public function deleteTranslation($translationId)
    {
        $translation = CategoryTranslation::findOrFail($translationId);
        $translation->delete();
        
        $this->loadTranslations();
        $this->setSuccessMessage(__('admin.translation_deleted'));
    }
    
    public function saveTranslation()
    {
        $this->validate([
            'locale' => 'required|in:en,pl',
            'name' => 'required|min:3',
        ]);
        
        if ($this->editingTranslationId) {
            // Update existing translation
            $translation = CategoryTranslation::findOrFail($this->editingTranslationId);
        } else {
            // Check if translation for this locale already exists
            $exists = $this->category->translations()->where('locale', $this->locale)->exists();
            if ($exists) {
                $this->setErrorMessage(__('admin.translation_exists'));
                return;
            }
            
            // Create a new translation
            $translation = new CategoryTranslation();
            $translation->category_id = $this->categoryId;
            $translation->locale = $this->locale;
        }
        
        $translation->name = $this->name;
        $translation->save();
        
        $this->loadTranslations();
        $this->resetFormFields();
        
        $this->setSuccessMessage(__('admin.translation_saved'));
    }
    
    public function cancelEdit()
    {
        $this->redirectRoute('admin.categories.index', navigate: true);
    }
    
    #[Layout('layouts.admin', ['header' => 'Manage Category Translations'])]
    public function render()
    {
        return view('livewire.admin.category-translations', [
            'category' => $this->category
        ]);
    }
} 