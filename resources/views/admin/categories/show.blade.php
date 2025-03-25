<x-admin-layout>
  <div class="container mx-auto p-6">
    <!-- Nagłówek z przyciskami i tytułem -->
    <div class="flex items-center justify-between mb-4">
      <!-- Lewa strona: przyciski -->
      <div class="flex space-x-2">
        <a href="{{ route('admin-dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
          Cofnij
        </a>
        <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
          Dodaj nowy post
        </a>
      </div>
      <!-- Środkowa część: tytuł -->
      <h1 class="text-2xl font-bold text-center flex-grow">
        Lista postów
      </h1>
      <!-- Prawa strona: pusta, żeby wyśrodkować tytuł -->
      <div class="w-32"></div>
    </div>
    @if (session('success'))
      <div class="mb-4 p-4 bg-green-600 text-white rounded">
          {{ session('success') }}
      </div>
    @endif
    <!-- Wrapper z poziomym przewijaniem -->
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr class="text-left">
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Tytuł</th>
            <th class="px-4 py-2 border">Post</th>
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
              <!-- AlpineJS - potwierdzenie usunięcia -->
              <div x-data="{ open: false }" class="inline-block">
                <button type="button" x-on:click="open = true" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition">
                  Usuń
                </button>
                <!-- Modal -->
                <div x-show="open" x-cloak class="fixed inset-0 flex items-center justify-center z-50">
                  <div class="fixed inset-0 bg-black opacity-50"></div>
                  <div class="bg-white rounded-lg p-6 z-50 shadow-lg w-96">
                    <h2 class="text-xl font-bold mb-4">Potwierdzenie usunięcia</h2>
                    <p class="mb-4">Czy na pewno chcesz usunąć ten post?</p>
                    <div class="flex justify-end space-x-2">
                      <button type="button" x-on:click="open = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Anuluj
                      </button>
                      <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
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
      {{ $posts->links() }}
    </div>
  </div>
</x-admin-layout>
