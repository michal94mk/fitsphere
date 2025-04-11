<?php

namespace App\Livewire\Admin;

use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class TrainersEdit extends Component
{
    use WithFileUploads;
    
    public $trainerId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $specialization = '';
    public $description = '';
    public $image;
    public $currentImage = '';
    public $changePassword = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('trainers')->ignore($this->trainerId)],
            'password' => $this->changePassword ? 'required|string|min:8|confirmed' : 'nullable',
            'specialization' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:1024', // 1MB Max
        ];
    }

    public function mount($id)
    {
        $this->trainerId = $id;
        $trainer = Trainer::findOrFail($id);
        
        $this->name = $trainer->name;
        $this->email = $trainer->email;
        $this->specialization = $trainer->specialization;
        $this->description = $trainer->description;
        $this->currentImage = $trainer->image;
    }

    public function render()
    {
        return view('livewire.admin.trainers-edit')->layout('layouts.admin', [
            'header' => 'Edytuj trenera'
        ]);
    }

    public function save()
    {
        $this->validate();
        
        $trainer = Trainer::findOrFail($this->trainerId);
        
        $imagePath = $trainer->image;
        if ($this->image) {
            // Remove old image if exists
            if ($trainer->image) {
                // Delete the old image file
                \Illuminate\Support\Facades\Storage::disk('public')->delete($trainer->image);
            }
            $imagePath = $this->image->store('trainers', 'public');
        }
        
        $trainer->name = $this->name;
        $trainer->email = $this->email;
        if ($this->changePassword && $this->password) {
            $trainer->password = Hash::make($this->password);
        }
        $trainer->specialization = $this->specialization;
        $trainer->description = $this->description;
        $trainer->image = $imagePath;
        
        $trainer->save();
        
        session()->flash('success', 'Dane trenera zostały zaktualizowane!');
        return redirect()->route('admin.trainers.index');
    }
} 