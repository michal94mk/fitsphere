<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CategoriesEdit extends Component
{
    use HasFlashMessages;
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
            'name' => ['required', 'string', 'min:2', 'max:50', Rule::unique('categories')->ignore($this->categoryId)],
        ];
    }
    
    protected function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'name']),
            'name.min' => __('validation.min.string', ['attribute' => 'name', 'min' => 2]),
            'name.max' => __('validation.max.string', ['attribute' => 'name', 'max' => 50]),
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
            
            $this->setSuccessMessage(__('admin.category_updated'));
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.category_update_error', ['error' => $e->getMessage()]));
        }
    }

    #[Layout('layouts.admin', ['header' => 'admin.edit_category'])]
    public function render()
    {
        return view('livewire.admin.categories-edit');
    }
} 