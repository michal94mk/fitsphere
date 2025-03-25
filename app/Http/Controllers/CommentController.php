<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user', 'post')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

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

        return redirect()->route('posts.show', $post->id)->with('success', 'Komentarz dodany!');
    }

    public function edit(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return redirect()->route('posts.show', $comment->post)->with('error', 'Nie masz uprawnień do edytowania tego komentarza.');
        }

        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return redirect()->route('posts.show', $comment->post)->with('error', 'Nie masz uprawnień do edytowania tego komentarza.');
        }

        $validated = $request->validate([
            'content' => 'required|max:500',
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        return redirect()->route('posts.show', $comment->post)->with('success', 'Komentarz zaktualizowany!');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Brak dostępu');
        }

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('admin.comments.index', $post)->with('success', 'Komentarz usunięty!');
    }
}
