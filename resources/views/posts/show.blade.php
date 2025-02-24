<x-blog-layout> 
    <!-- Post Content -->
    <article>
        <!-- Post Title -->
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        <!-- Post Content -->
        <p class="text-gray-700">{{ $post->content }}</p>
        <!-- Post Meta (Author and Date) -->
        <div class="mt-4 text-sm text-gray-500">
            <p>Author: {{ $post->user->name }}</p>
            <p>Published on: {{ $post->created_at->format('d.m.Y') }}</p>
        </div>
    </article>

    <!-- Comments Section -->
    <section class="mt-6">
        <!-- Comments Header -->
        <h2 class="text-2xl font-semibold mb-4">Comments</h2>

        <!-- Success or Error Message -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 p-4 bg-red-600 text-white rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Loop through each comment -->
        @foreach($comments as $comment)
            <div class="mb-4 p-4 bg-gray-200 rounded">
                <!-- Comment Content -->
                <p class="text-gray-900">{{ $comment->content }}</p>
                <!-- Comment Meta (User and Date) -->
                <p class="text-sm text-gray-500">Added by {{ $comment->user->name }} - {{ $comment->created_at->format('d.m.Y H:i') }}</p>
            </div>
        @endforeach

        <!-- Pagination for Comments -->
        <div class="mt-6 mb-4">
            {{ $comments->links() }}
        </div>
        
        <!-- Comment Form (only visible for authenticated users) -->
        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                @csrf
                <!-- Textarea for comment -->
                <textarea name="content" rows="10" required class="w-full p-2 border rounded max-h-40 resize-none" style="max-height: 180px;"></textarea>
                <!-- Submit Button -->
                <button type="submit" class="mt-2 px-4 py-2 bg-gray-800 text-gray-300 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Add Comment</button>
            </form>
        @endauth
    </section>
</x-blog-layout>
