<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CategoriesEdit extends Component
{
    public $categoryId;
    
    public $name = '';

    public function mount($id)
    {
        $this->categoryId = $id;
        $category = Category::findOrFail($id);
        
        $this->name = $category->name;
    }
    
    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($this->categoryId)],
        ];
    }
    
    protected function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'name']),
            'name.max' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            'name.unique' => __('validation.unique', ['attribute' => 'name']),
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            $category = Category::findOrFail($this->categoryId);
            
            $category->name = $this->name;
            
            $category->save();
            
            session()->flash('success', __('categories.category_updated'));
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('error', __('categories.category_update_error', ['error' => $e->getMessage()]));
        }
    }

    #[Layout('layouts.admin', ['header' => 'Edit Category'])]
    public function render()
    {
        return view('livewire.admin.categories-edit');
    }
} 