<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class CategoriesIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $categoryIdBeingDeleted = null;
    public $confirmingCategoryDeletion = false;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortField()
    {
        $this->resetPage();
    }

    public function setSorting($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmCategoryDeletion($id)
    {
        $this->categoryIdBeingDeleted = $id;
        $this->confirmingCategoryDeletion = true;
    }

    public function deleteCategory()
    {
        if (!$this->categoryIdBeingDeleted) {
            session()->flash('error', "Nie można usunąć kategorii, brak identyfikatora.");
            $this->confirmingCategoryDeletion = false;
            return;
        }
        
        try {
            $category = Category::findOrFail($this->categoryIdBeingDeleted);
            $categoryName = $category->name;
            $category->delete();
            
            session()->flash('success', "Kategoria '{$categoryName}' została pomyślnie usunięta.");
        } catch (\Exception $e) {
            session()->flash('error', "Wystąpił błąd podczas usuwania kategorii: {$e->getMessage()}");
        }
        
        $this->confirmingCategoryDeletion = false;
        $this->categoryIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingCategoryDeletion = false;
        $this->categoryIdBeingDeleted = null;
    }

    #[Layout('layouts.admin', ['header' => 'Zarządzanie kategoriami'])]
    public function render()
    {
        $categories = Category::query()
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.categories-index', compact('categories'));
    }
}
