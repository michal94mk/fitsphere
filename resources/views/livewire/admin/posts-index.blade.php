<div>
    <div class="container mx-auto p-6">
        <!-- Nagłówek z tytułem i przyciskami -->
        <div class="flex flex-col sm:items-start mb-4">
            <!-- Tytuł -->
            <h1 class="text-2xl font-bold text-center sm:text-left mb-4">
                Lista postów
            </h1>

            <!-- Przyciski akcji -->
            <div class="flex flex-row space-x-2 justify-center sm:justify-start">
                <a href="{{ route('admin.dashboard') }}" 
                class="bg-gray-500 text-white px-4 py-2 h-10 rounded-md hover:bg-gray-600 transition flex items-center justify-center whitespace-nowrap">
                    Cofnij
                </a>
                <a href="{{ route('admin.posts.create') }}" 
                class="bg-blue-500 text-white px-4 py-2 h-10 rounded-md hover:bg-blue-600 transition flex items-center justify-center whitespace-nowrap">
                    Dodaj nowy post
                </a>
            </div>
        </div>

        <!-- Komunikat o sukcesie -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabela postów -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Tytuł</th>
                        <th class="px-4 py-2 border">Obrazek</th>
                        <th class="px-4 py-2 border">Autor</th>
                        <th class="px-4 py-2 border">Slug</th>
                        <th class="px-4 py-2 border">Kategoria</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Data</th>
                        <th class="px-4 py-2 border">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr class="hover:bg-gray-100 border-b">
                        <td class="px-4 py-2 border text-center">{{ $post->id }}</td>
                        <td class="px-4 py-2 border font-semibold">{{ $post->title }}</td>
                        <td class="px-4 py-2 border text-center">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-16 h-auto rounded">
                            @else
                                <span class="text-gray-500 text-sm">Brak obrazka</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border">{{ optional($post->user)->name ?? 'Brak autora' }}</td>
                        <td class="px-4 py-2 border break-all">{{ $post->slug }}</td>
                        <td class="px-4 py-2 border">{{ optional($post->category)->name ?? 'Brak kategorii' }}</td>
                        <td class="px-4 py-2 border text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-md {{ $post->status === 'published' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border text-center">{{ optional($post->created_at)->format('Y-m-d') ?? 'Brak daty' }}</td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition inline-block mb-1">
                                Edytuj
                            </a>
                            <button wire:click="deletePost({{ $post->id }})" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition">
                                Usuń
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginacja -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div> 