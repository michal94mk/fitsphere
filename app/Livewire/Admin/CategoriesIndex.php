<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class CategoriesIndex extends Component
{
    use WithPagination, HasFlashMessages;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $categoryIdBeingDeleted = null;
    public $confirmingCategoryDeletion = false;
    public $page = 1;

    protected $queryString = ['search', 'sortField', 'sortDirection', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortField()
    {
        $this->resetPage();
    }

    public function sortBy($field)
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
        $this->clearMessages();
        
        if (!$this->categoryIdBeingDeleted) {
            $this->setErrorMessage(__('admin.category_delete_missing_id'));
            $this->confirmingCategoryDeletion = false;
            return;
        }
        
        try {
            $category = Category::findOrFail($this->categoryIdBeingDeleted);
            $categoryName = $category->name;
            
            // Check if category has posts
            $postsCount = $category->posts()->count();
            if ($postsCount > 0) {
                $this->setErrorMessage(__('admin.category_delete_has_posts', ['name' => $categoryName, 'count' => $postsCount]));
                $this->confirmingCategoryDeletion = false;
                $this->categoryIdBeingDeleted = null;
                return;
            }
            
            $category->delete();
            
            $this->setSuccessMessage(__('admin.category_deleted', ['name' => $categoryName]));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.category_delete_error', ['error' => $e->getMessage()]));
        }
        
        $this->confirmingCategoryDeletion = false;
        $this->categoryIdBeingDeleted = null;
    }

    public function cancelDeletion()
    {
        $this->confirmingCategoryDeletion = false;
        $this->categoryIdBeingDeleted = null;
    }

    #[Layout('layouts.admin', ['header' => 'admin.category_management'])]
    public function render()
    {
        $categories = Category::query()
            ->with(['translations'])
            ->withCount('posts')
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.categories-index', compact('categories'));
    }
}
