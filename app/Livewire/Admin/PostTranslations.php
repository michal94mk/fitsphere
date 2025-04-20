<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PostTranslations extends Component
{
    public $postId;
    public $post;
    
    // Translation fields
    public $locale = 'pl'; // Default to Polish for new translations
    public $title;
    public $excerpt;
    public $content;
    
    // Existing translations list
    public $translations = [];
    public $editingTranslationId = null;
    
    /**
     * Mount the component
     */
    public function mount($id)
    {
        $this->postId = $id;
        $this->post = Post::findOrFail($id);
        $this->loadTranslations();
    }
    
    /**
     * Load translations for this post
     */
    public function loadTranslations()
    {
        $this->translations = $this->post->translations()->get()->toArray();
    }
    
    /**
     * Reset form fields
     */
    public function resetFormFields()
    {
        $this->editingTranslationId = null;
        $this->locale = 'pl';
        $this->title = '';
        $this->excerpt = '';
        $this->content = '';
    }
    
    /**
     * Start editing an existing translation
     */
    public function editTranslation($translationId)
    {
        $translation = PostTranslation::findOrFail($translationId);
        
        $this->editingTranslationId = $translation->id;
        $this->locale = $translation->locale;
        $this->title = $translation->title;
        $this->excerpt = $translation->excerpt;
        $this->content = $translation->content;
    }
    
    /**
     * Delete a translation
     */
    public function deleteTranslation($translationId)
    {
        $translation = PostTranslation::findOrFail($translationId);
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
            'title' => 'required|min:3',
            'content' => 'required',
        ]);
        
        // Generate slug from title
        $slug = Str::slug($this->title);
        
        if ($this->editingTranslationId) {
            // Update existing translation
            $translation = PostTranslation::findOrFail($this->editingTranslationId);
        } else {
            // Check if translation for this locale already exists
            $exists = $this->post->translations()->where('locale', $this->locale)->exists();
            if ($exists) {
                session()->flash('error', 'Tłumaczenie dla wybranego języka już istnieje.');
                return;
            }
            
            // Create a new translation
            $translation = new PostTranslation();
            $translation->post_id = $this->postId;
            $translation->locale = $this->locale;
        }
        
        // Update translation data
        $translation->title = $this->title;
        $translation->slug = $slug;
        $translation->excerpt = $this->excerpt;
        $translation->content = $this->content;
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
    #[Layout('layouts.admin', ['header' => 'Zarządzaj tłumaczeniami artykułu'])]
    public function render()
    {
        return view('livewire.admin.post-translations', [
            'post' => $this->post
        ]);
    }
} 