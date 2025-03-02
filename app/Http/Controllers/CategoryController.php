<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function show(Post $post)
    {
        $comments = $post->comments()->latest()->paginate(3);
        return view('admin.posts.show', compact('post', 'comments'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        Category::create($data);
    
        return redirect()->route('admin.categories.index')->with('success', 'Kategoria została dodana');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update($data);
    
        return redirect()->route('admin.categories.index')->with('success', 'Kategoria została zaktualizowana');
    }
    

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategoria została usunięta!');
    }
}
