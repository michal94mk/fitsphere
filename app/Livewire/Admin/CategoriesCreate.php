<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Layout;

class CategoriesCreate extends Component
{
    public $name = '';
    
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
    
    protected function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'name']),
        ];
    }

    public function save()
    {
        $this->validate();
        
        try {
            Category::create([
                'name' => $this->name
            ]);
            
            session()->flash('success', __('categories.category_created'));
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('error', __('categories.category_create_error', ['error' => $e->getMessage()]));
        }
    }

    #[Layout('layouts.admin', ['header' => 'Add New Category'])]
    public function render()
    {
        return view('livewire.admin.categories-create');
    }
} 