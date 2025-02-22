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

        @auth
            <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                @csrf
                <textarea name="content" rows="4" required class="w-full p-2 border rounded"></textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">Dodaj Komentarz</button>
            </form>
        @endauth

        @foreach($post->comments as $comment)
            <div class="mb-4 p-4 bg-gray-200 rounded">
                <p class="text-gray-900">{{ $comment->content }}</p>
                <p class="text-sm text-gray-500">Dodano przez {{ $comment->user->name }} - {{ $comment->created_at->format('d.m.Y H:i') }}</p>
            </div>
        @endforeach
    </section>
</x-blog-layout>