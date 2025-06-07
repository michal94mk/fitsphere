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
    
    public $locale = 'pl';
    public $name;
    public $description;
    
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
        $this->description = '';
    }
    
    public function editTranslation($translationId)
    {
        $translation = CategoryTranslation::findOrFail($translationId);
        
        $this->editingTranslationId = $translation->id;
        $this->locale = $translation->locale;
        $this->name = $translation->name;
        $this->description = $translation->description;
    }
    
    public function deleteTranslation($translationId)
    {
        $translation = CategoryTranslation::findOrFail($translationId);
        $translation->delete();
        
        $this->loadTranslations();
        session()->flash('success', __('admin.translation_deleted'));
    }
    
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
                session()->flash('error', __('admin.translation_exists'));
                return;
            }
            
            // Create a new translation
            $translation = new CategoryTranslation();
            $translation->category_id = $this->categoryId;
            $translation->locale = $this->locale;
        }
        
        $translation->name = $this->name;
        $translation->slug = $slug;
        $translation->description = $this->description;
        $translation->save();
        
        $this->loadTranslations();
        $this->resetFormFields();
        
        session()->flash('success', __('admin.translation_saved'));
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