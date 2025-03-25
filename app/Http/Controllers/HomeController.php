<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class HomeController extends Controller
{
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
        return view('about', compact('trainers'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function terms()
    {
        return view('terms');
    }
}
