<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesIndex extends Component
{
    use WithPagination;
    
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        session()->flash('success', 'Kategoria została pomyślnie usunięta.');
    }
    
    public function render()
    {
        return view('livewire.admin.categories-index', [
            'categories' => Category::latest()->paginate(10)
        ])->layout('layouts.admin', ['header' => 'Lista kategorii']);
    }
} 