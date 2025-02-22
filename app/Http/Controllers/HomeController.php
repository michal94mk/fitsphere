<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'published')->latest()->paginate(5);
        return view('home', compact('posts'));
    }
}