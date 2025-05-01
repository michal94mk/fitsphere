<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

class CategoriesCreate extends Component
{
    #[Rule('required|string|max:255', message: 'Category name is required.')]
    public $name = '';
    
    protected $messages = [
        'name.required' => 'Category name is required.',
    ];

    #[Layout('layouts.admin', ['header' => 'Add New Category'])]
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
            
            session()->flash('success', 'Category has been successfully added!');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while adding the category: ' . $e->getMessage());
        }
    }
} 