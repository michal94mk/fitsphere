<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PostDetails extends Component
{
    use WithPagination;

    public int $postId;
    public $post;
    public string $newComment = '';

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->post = Post::with('user')->findOrFail($this->postId);
    }

    public function addComment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Musisz być zalogowany, aby dodać komentarz.');
            return;
        }

        $this->validate([
            'newComment' => 'required|min:3|max:500'
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';
        session()->flash('success', 'Komentarz został dodany.');
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.post-details', [
            'comments' => Comment::where('post_id', $this->post->id)
                ->with('user')
                ->latest()
                ->paginate(5)
        ]);
    }
}
