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
    
    #[FormRule('required|string|max:255', message: 'Nazwa kategorii jest wymagana.')]
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
            'name.required' => 'Nazwa kategorii jest wymagana.',
            'name.max' => 'Nazwa kategorii nie może być dłuższa niż 255 znaków.',
            'name.unique' => 'Kategoria o tej nazwie już istnieje.',
        ];
    }

    #[Layout('layouts.admin', ['header' => 'Edytuj kategorię'])]
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
            
            session()->flash('success', 'Kategoria została zaktualizowana!');
            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd podczas aktualizacji kategorii: ' . $e->getMessage());
        }
    }
} 