<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class CategoriesCreate extends Component
{
    #[Rule('required|string|max:255', message: 'Nazwa kategorii jest wymagana.')]
    public $name = '';
    

    protected $messages = [
        'name.required' => 'Nazwa kategorii jest wymagana.',
    ];

    #[Layout('layouts.admin', ['header' => 'Dodaj nową kategorię'])]
    public function render()
    {
        return view('livewire.admin.categories-create');
    }

    public function save()
    {
        $this->validate();
        
        try {
            Category::create([
                'name' => $this->name
            ]);
            
            session()->flash('success', 'Kategoria została pomyślnie dodana!');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas dodawania kategorii: ' . $e->getMessage());
        }
    }
} 