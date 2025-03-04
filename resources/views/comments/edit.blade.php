<x-blog-layout>
    <div class="max-w-4xl mx-auto py-12 px-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold mb-6 text-center">Edycja komentarza</h1>

        <!-- Wyświetlanie błędów walidacji -->
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-600 text-white rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('comments.update', $comment) }}">
            @csrf
            @method('PUT')
            <textarea name="content" rows="5" class="w-full p-3 border rounded-lg resize-none" placeholder="Edytuj swój komentarz...">{{ old('content', $comment->content) }}</textarea>

            <button type="submit" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all">
                Zaktualizuj komentarz
            </button>
        </form>
    </div>
</x-blog-layout>
