<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;

class PostDetails extends Component
{
    use WithPagination;

    public int $postId;
    public $post;
    public $translation;
    public string $newComment = '';

    public function mount($postId)
    {
        $this->postId = $postId;
        $this->post = Post::with('user')->findOrFail($this->postId);
        
        $livewireLocale = session()->get('livewire_locale');
        
        // Get translation for current locale if available
        $this->loadTranslation($livewireLocale);
        
        // Increment view count
        $this->post->increment('view_count');
        
        // Also track detailed view information
        $ip = Request::ip();
        $userAgent = Request::header('User-Agent');
        $userId = Auth::id() ?? null;
        
        // Optional: track unique views using IP + post combination
        $existingView = PostView::where('post_id', $this->postId)
            ->where('ip_address', $ip)
            ->whereDate('created_at', now()->toDateString())
            ->first();
            
        if (!$existingView) {
            PostView::create([
                'post_id' => $this->postId,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'user_id' => $userId
            ]);
        }
    }
    
    /**
     * Load translation for the current locale
     * 
     * Retrieves the appropriate translation for the post based on 
     * the specified locale or falls back to the current application locale.
     *
     * @param string|null $locale Optional language code to load specific translation
     */
    protected function loadTranslation($locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $this->translation = $this->post->translation($locale);
    }
    
    /**
     * Listen for language change events and update content accordingly
     * 
     * This handler is triggered when the language is switched anywhere
     * in the application. It loads the post's translation for the new locale
     * and refreshes the component display without a page reload.
     *
     * @param string $locale The new language code (en/pl)
     */
    #[On('language-changed')]
    public function handleLanguageChange($locale)
    {
        $this->loadTranslation($locale);
        $this->dispatch('$refresh');
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

    #[Layout('layouts.blog')]
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
