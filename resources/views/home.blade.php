<x-blog-layout>
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">Najnowsze posty</h2>
        
        @foreach($posts as $post)
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 mb-6 relative border border-gray-200 hover:border-gray-300">
                <h3 class="text-2xl font-semibold text-gray-800 hover:text-blue-600 transition-colors duration-200">
                    <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">
                        {{ $post->title }}
                    </a>
                </h3>
                <p class="text-gray-600 text-sm mb-4">Dodano: {{ $post->created_at->format('d.m.Y') }}</p>
                <p class="text-gray-800 text-base mb-4">{{ $post->excerpt }}</p>

                <!-- Liczba komentarzy, umieszczona w prawym dolnym rogu -->
                <p class="absolute bottom-4 right-4 text-sm text-gray-500">
                    Komentarze: {{ $post->comments->count() }}
                </p>

                <a href="{{ route('posts.show', $post) }}" class="text-blue-500 font-semibold mt-2 inline-block hover:text-blue-700 transition-colors duration-200">Czytaj więcej</a>
            </div>
        @endforeach

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</x-blog-layout>
