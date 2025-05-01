<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule as FormRule;

class CategoriesEdit extends Component
{
    public $categoryId;
    
    #[FormRule('required|string|max:255', message: 'Category name is required.')]
    public $name = '';

    public function mount($id)
    {
        $this->categoryId = $id;
        $category = Category::findOrFail($id);
        
        $this->name = $category->name;
    }
    
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($this->categoryId)],
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Category name is required.',
            'name.max' => 'Category name cannot be longer than 255 characters.',
            'name.unique' => 'A category with this name already exists.',
        ];
    }

    #[Layout('layouts.admin', ['header' => 'Edit Category'])]
    public function render()
    {
        return view('livewire.admin.categories-edit');
    }

    public function save()
    {
        $this->validate();
        
        try {
            $category = Category::findOrFail($this->categoryId);
            
            $category->name = $this->name;
            
            $category->save();
            
            session()->flash('success', 'Category has been updated!');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the category: ' . $e->getMessage());
        }
    }
} 