<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;

class CategoriesEdit extends Component
{
    public $category;
    
    protected function rules()
    {
        return [
            'category.name' => 'required|min:2|unique:categories,name,' . $this->category->id
        ];
    }
    
    public function mount($id)
    {
        $this->category = Category::findOrFail($id);
    }
    
    public function update()
    {
        $this->validate();
        $this->category->save();
        
        session()->flash('success', 'Kategoria została pomyślnie zaktualizowana.');
        return redirect()->route('admin.categories.index');
    }
    
    public function render()
    {
        return view('livewire.admin.categories-edit')
            ->layout('layouts.admin', ['header' => 'Edytuj kategorię']);
    }
} 