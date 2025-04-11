<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;

class CategoriesCreate extends Component
{
    public $name;
    
    protected $rules = [
        'name' => 'required|min:2|unique:categories,name'
    ];
    
    public function store()
    {
        $this->validate();
        
        $category = new Category();
        $category->name = $this->name;
        $category->save();
        
        session()->flash('success', 'Kategoria została pomyślnie utworzona.');
        return redirect()->route('admin.categories.index');
    }
    
    public function render()
    {
        return view('livewire.admin.categories-create')
            ->layout('layouts.admin', ['header' => 'Dodaj nową kategorię']);
    }
} 