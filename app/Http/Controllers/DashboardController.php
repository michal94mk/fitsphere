<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class DashboardController extends Controller
{   
    public function index()
    {
        $userCount = User::count();
        $postCount = Post::count();
        $commentCount = Comment::count();
    
        return view('admin.dashboard', compact('userCount', 'postCount', 'commentCount'));
    }
}
