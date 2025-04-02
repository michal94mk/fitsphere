<div>    
    <!-- Post Content -->
    <div class="max-w-4xl mx-auto py-12 px-6 bg-white shadow-lg rounded-lg">

        <h1 class="text-4xl font-bold text-center mb-4">{{ $post->title }}</h1>
        <p class="text-center text-gray-500 text-sm mb-8">
            <span class="font-bold">Autor:</span> {{ $post->user->name }} | 
            <span class="font-bold">Opublikowano:</span> {{ $post->created_at->format('d.m.Y') }}
        </p>

        @if($post->image)
            <div class="w-full flex justify-center mb-8">
                <img src="{{ asset('storage/' . $post->image) }}" 
                     alt="{{ $post->title }}" 
                     class="w-96 h-96 object-cover rounded-lg shadow-lg">
            </div>
        @endif

        <p class="text-gray-700 text-lg leading-relaxed mb-8 break-words">{{ $post->content }}</p>
    </div>

    <!-- Comments Section -->
    <div class="max-w-4xl mx-auto mt-12 mb-12 p-6 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-3xl font-semibold mb-6 text-center">Komentarze</h2>

        <!-- Success or Error Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 p-4 bg-red-600 text-white rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- If there are no comments -->
        @if($comments->isEmpty())
            <p class="text-center text-gray-500">Brak komentarzy</p>
        @else
            <!-- Display each comment -->
            @foreach($comments as $comment)
                <div class="mb-6 p-4 bg-white rounded-lg shadow">
                    <p class="text-gray-900 text-lg break-words">{{ $comment->content }}</p>
                    <p class="text-sm text-gray-500 mt-2">
                        Dodane przez {{ $comment->user->name }} | 
                        {{ $comment->created_at->format('d.m.Y H:i') }}
                    </p>

                    <!-- Allow the author to edit their comment -->
                    @if($comment->user_id === Auth::id())
                        <div class="mt-2 text-right">
                            <a href="{{ route('comments.edit', $comment) }}" class="text-blue-500 hover:underline">Edytuj</a>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Pagination for comments -->
            <div class="mt-6">
                {{ $comments->links() }}
            </div>
        @endif

        <!-- Add Comment Section (for authenticated users) -->
        @auth
            <form wire:submit.prevent="addComment" class="mt-6">
                <textarea wire:model="newComment" rows="5" required class="w-full p-3 border rounded-lg resize-none mb-4" placeholder="Dodaj swój komentarz..."></textarea>
                <button type="submit" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">
                    Dodaj komentarz
                </button>
            </form>
        @else
            <!-- Prompt for users to log in if they are not authenticated -->
            <p class="mt-4 text-center text-gray-500">
                Aby dodać komentarz, musisz <a href="{{ route('login') }}" class="text-blue-500 hover:underline">zalogować się</a>.
            </p>
        @endauth
    </div>

</div>
