<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class HomeController extends Controller
{
    public function getLatest5()
    {
        $posts = Post::withCount('comments')
                    ->latest()
                    ->paginate(5);
        return view('home', compact('posts'));
    }

    public function getAllPosts()
    {
        $posts = Post::withCount('comments')
                    ->latest()
                    ->paginate(3);
        return view('posts', compact('posts'));
    }

    public function show(Post $post)
    {
        $comments = $post->comments()->latest()->paginate(3);
        return view('post_show', compact('post', 'comments'));
    }
    

    public function index()
    {
        $posts = Post::withCount('comments')
                    ->latest()
                    ->paginate(5);
        return view('home', compact('posts'));
    }

    public function about()
    {
        $trainers = User::where('role', 'trainer')->paginate(9);
        return view('livewire.about', compact('trainers'));
    }

    public function terms()
    {
        return view('livewire.terms');
    }
}
