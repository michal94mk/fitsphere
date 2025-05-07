<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\App;
use App\Services\LogService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostDetails extends Component
{
    use WithPagination;

    public int $postId;
    public $post;
    public $translation;
    public string $newComment = '';
    protected $logService;
    
    public function boot()
    {
        $this->logService = app(LogService::class);
    }

    public function mount($postId)
    {
        try {
            $this->postId = $postId;
            $this->post = Post::with('user')->findOrFail($this->postId);
            $this->loadTranslation();
            
            // Track post view
            $this->trackPostView();
        } catch (ModelNotFoundException $e) {
            $this->logService->error('Post not found', [
                'post_id' => $postId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.post_not_found'));
            return $this->redirect(route('posts.list'), navigate: true);
        } catch (\Throwable $e) {
            $this->logService->error('Error loading post details', [
                'post_id' => $postId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.unexpected_error'));
            return $this->redirect(route('posts.list'), navigate: true);
        }
    }
    
    protected function trackPostView()
    {
        try {
            // Increment view count
            $this->post->increment('view_count');
            
            // Track detailed view information
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
        } catch (\Throwable $e) {
            // Log but don't disrupt user experience for view tracking
            $this->logService->error('Error tracking post view', [
                'post_id' => $this->postId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    protected function loadTranslation()
    {
        $locale = App::getLocale();
        $this->translation = $this->post->translation($locale);
    }
    
    #[On('switch-locale')]
    public function handleLanguageChange($locale)
    {
        $this->loadTranslation();
    }

    public function addComment()
    {
        try {
            if (!Auth::check()) {
                session()->flash('error', __('common.login_to_comment'));
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
            session()->flash('success', __('common.comment_added'));
            $this->resetPage();
        } catch (\Throwable $e) {
            $this->logService->error('Error adding comment', [
                'post_id' => $this->postId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            session()->flash('error', __('common.comment_add_error'));
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        try {
            if (!$this->post) {
                return $this->redirect(route('posts.list'), navigate: true);
            }
            
            return view('livewire.post-details', [
                'comments' => Comment::where('post_id', $this->post->id)
                    ->with('user')
                    ->latest()
                    ->paginate(5)
            ]);
        } catch (\Throwable $e) {
            $this->logService->error('Error rendering post details', [
                'post_id' => $this->postId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', __('common.unexpected_error'));
            return $this->redirect(route('posts.list'), navigate: true);
        }
    }
}