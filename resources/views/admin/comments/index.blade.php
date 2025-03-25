<x-admin-layout>
  <div class="container mx-auto p-6">
    <!-- Nagłówek z tytułem i przyciskami -->
    <div class="flex flex-col sm:items-start mb-4">
      <!-- Tytuł -->
      <h1 class="text-2xl font-bold text-center sm:text-left mb-4">
        Lista komentarzy
      </h1>

      <!-- Przyciski akcji -->
      <div class="flex flex-row space-x-2 justify-center sm:justify-start">
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 text-white px-4 py-2 h-10 rounded-md hover:bg-gray-600 transition flex items-center justify-center whitespace-nowrap">
          Cofnij
        </a>
      </div>
    </div>

    @if (session('success'))
      <div class="mb-4 p-4 bg-green-600 text-white rounded">
          {{ session('success') }}
      </div>
    @endif

    <!-- Tabela komentarzy -->
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr class="text-left">
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Treść</th>
            <th class="px-4 py-2 border">Autor</th>
            <th class="px-4 py-2 border">Post</th>
            <th class="px-4 py-2 border">Data</th>
            <th class="px-4 py-2 border">Akcje</th>
          </tr>
        </thead>
        <tbody>
          @foreach($comments as $comment)
          <tr class="hover:bg-gray-100 border-b">
            <td class="px-4 py-2 border text-center">{{ $comment->id }}</td>
            <td class="px-4 py-2 border">{{ \Illuminate\Support\Str::limit($comment->content, 50) }}</td>
            <td class="px-4 py-2 border">{{ $comment->user->name ?? 'Brak autora' }}</td>
            <td class="px-4 py-2 border">
              <a href="{{ route('posts.show', $comment->post) }}" class="text-blue-500 hover:underline">
                {{ $comment->post->title ?? 'Brak posta' }}
              </a>
            </td>
            <td class="px-4 py-2 border text-center">{{ $comment->created_at->format('Y-m-d') }}</td>
            <td class="px-4 py-2 border text-center">
              <!-- AlpineJS - potwierdzenie usunięcia -->
              <div x-data="{ open: false }" class="inline-block">
                <button @click="open = true" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition">
                  Usuń
                </button>
                <!-- Modal -->
                <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center z-50">
                  <div class="fixed inset-0 bg-black opacity-50"></div>
                  <div class="bg-white rounded-lg p-6 z-50 shadow-lg w-96">
                    <h2 class="text-xl font-bold mb-4">Potwierdzenie usunięcia</h2>
                    <p class="mb-4">Czy na pewno chcesz usunąć ten komentarz?</p>
                    <div class="flex justify-end space-x-2">
                      <button @click="open = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Anuluj
                      </button>
                      <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                          Usuń
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Koniec AlpineJS -->
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $comments->links() }}
    </div>
  </div>
</x-admin-layout>
