<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\UserTranslation;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\Attributes\Layout;

class UserTranslations extends Component
{
    use HasFlashMessages;
    public $userId;
    public $user;
    
    // Translation fields
    public $locale = 'pl'; // Default to Polish for new translations
    public $name;
    public $specialization;
    public $description;
    public $bio;
    public $specialties;
    
    // Existing translations list
    public $translations = [];
    public $editingTranslationId = null;
    
    public function mount($id)
    {
        $this->userId = $id;
        $this->user = User::findOrFail($id);
        
        // Verify this user is a trainer or admin (has translation needs)
        if (!$this->user->isTrainer() && !$this->user->isAdmin()) {
            $this->setErrorMessage(__('admin.only_trainers_can_have_translations'));
            return redirect()->route('admin.users.index');
        }
        
        $this->loadTranslations();
    }
    
    public function loadTranslations()
    {
        $this->translations = $this->user->translations()->get()->toArray();
    }
    
    public function resetFormFields()
    {
        $this->editingTranslationId = null;
        $this->locale = 'pl';
        $this->name = '';
        $this->specialization = '';
        $this->description = '';
        $this->bio = '';
        $this->specialties = '';
    }

    public function editTranslation($translationId)
    {
        $translation = UserTranslation::findOrFail($translationId);
        
        $this->editingTranslationId = $translation->id;
        $this->locale = $translation->locale;
        $this->name = $translation->name;
        $this->specialization = $translation->specialization;
        $this->description = $translation->description;
        $this->bio = $translation->bio;
        $this->specialties = $translation->specialties;
    }

    public function deleteTranslation($translationId)
    {
        $translation = UserTranslation::findOrFail($translationId);
        $translation->delete();
        
        $this->loadTranslations();
        $this->setSuccessMessage(__('admin.translation_deleted'));
    }

    public function saveTranslation()
    {
        $this->validate([
            'locale' => 'required|in:en,pl',
            'name' => 'nullable|max:255',
            'specialization' => 'nullable|max:255',
            'description' => 'nullable|string',
            'bio' => 'nullable|string',
            'specialties' => 'nullable|max:500',
        ]);
        
        if ($this->editingTranslationId) {
            // Update existing translation
            $translation = UserTranslation::findOrFail($this->editingTranslationId);
        } else {
            // Check if translation for this locale already exists
            $exists = $this->user->translations()->where('locale', $this->locale)->exists();
            if ($exists) {
                $this->setErrorMessage(__('admin.translation_exists'));
                return;
            }
            
            // Create a new translation
            $translation = new UserTranslation();
            $translation->user_id = $this->userId;
            $translation->locale = $this->locale;
        }
        
        // Update translation data
        $translation->name = $this->name;
        $translation->specialization = $this->specialization;
        $translation->description = $this->description;
        $translation->bio = $this->bio;
        $translation->specialties = $this->specialties;
        $translation->save();
        
        $this->loadTranslations();
        $this->resetFormFields();
        
        $this->setSuccessMessage(__('admin.translation_saved'));
    }
    
    public function cancelEdit()
    {
        if ($this->user->isTrainer()) {
            $this->redirectRoute('admin.trainers.index', navigate: true);
        } else {
            $this->redirectRoute('admin.users.index', navigate: true);
        }
    }
    
    #[Layout('layouts.admin', ['header' => 'admin.manage_user_translations'])]
    public function render()
    {
        return view('livewire.admin.user-translations', [
            'user' => $this->user
        ]);
    }
}
