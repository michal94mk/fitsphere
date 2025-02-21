<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|max:500',
        ]);

        Comment::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Komentarz dodany!');
    }
}
