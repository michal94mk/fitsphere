<x-blog-layout> 
    <article>
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        <p class="text-gray-700">{{ $post->content }}</p>
        <div class="mt-4 text-sm text-gray-500">
            <p>Autor: {{ $post->user->name }}</p>
            <p>Opublikowano: {{ $post->created_at->format('d.m.Y') }}</p>
        </div>
    </article>

    <section class="mt-6">
        <h2 class="text-2xl font-semibold mb-4">Komentarze</h2>

        <!-- Komunikat o sukcesie -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-4 p-4 bg-red-600 text-white rounded">
                {{ session('error') }}
            </div>
        @endif

        @foreach($comments as $comment)
            <div class="mb-4 p-4 bg-gray-200 rounded">
                <p class="text-gray-900">{{ $comment->content }}</p>
                <p class="text-sm text-gray-500">Dodano przez {{ $comment->user->name }} - {{ $comment->created_at->format('d.m.Y H:i') }}</p>
            </div>
        @endforeach

        <!-- Paginacja -->
        <div class="mt-6 mb-4"> <!-- Dodano więcej przestrzeni nad i pod paginacją -->
            {{ $comments->links() }}
        </div>
        
        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                @csrf
                <textarea name="content" rows="10" required class="w-full p-2 border rounded max-h-40 resize-none" style="max-height: 180px;"></textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-gray-800 text-gray-300 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Dodaj Komentarz</button>
            </form>
        @endauth
    </section>
</x-blog-layout>
