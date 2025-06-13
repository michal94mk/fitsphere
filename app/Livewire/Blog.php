<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public $search;
    public $category;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $page;

    public function render()
    {
        $cacheKey = 'blog.' . $this->search . '.' . $this->category . '.' . $this->sortField . '.' . $this->sortDirection . '.' . $this->page;
        
        $data = cache()->remember($cacheKey, 300, function () {
            $posts = Post::query()
                ->with(['user', 'category', 'translations'])
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('content', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->category, function ($query) {
                    $query->where('category_id', $this->category);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);

            $categories = Category::with('translations')->get();

            return [
                'posts' => $posts,
                'categories' => $categories
            ];
        });

        return view('livewire.blog', [
            'posts' => $data['posts'],
            'categories' => $data['categories']
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->clearCache();
    }

    public function updatingCategory()
    {
        $this->resetPage();
        $this->clearCache();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->clearCache();
    }

    public function updatingPage()
    {
        $this->clearCache();
    }

    protected function clearCache()
    {
        $cacheKey = 'blog.' . $this->search . '.' . $this->category . '.' . $this->sortField . '.' . $this->sortDirection . '.' . $this->page;
        cache()->forget($cacheKey);
    }
} 