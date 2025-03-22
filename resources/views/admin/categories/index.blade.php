<x-admin-layout>
  <div class="container mx-auto p-6">
    <!-- Nagłówek z tytułem i przyciskami -->
    <div class="flex flex-col sm:items-start mb-4">
      <!-- Tytuł -->
      <h1 class="text-2xl font-bold text-center sm:text-left mb-4">
        Lista kategorii
      </h1>

      <!-- Przyciski akcji -->
      <div class="flex flex-row space-x-2 justify-center sm:justify-start">
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 text-white px-4 py-2 h-10 rounded-md hover:bg-gray-600 transition flex items-center justify-center whitespace-nowrap">
          Cofnij
        </a>
        <a href="{{ route('admin.categories.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 h-10 rounded-md hover:bg-blue-600 transition flex items-center justify-center whitespace-nowrap">
          Dodaj nową kategorię
        </a>
      </div>
    </div>

    <!-- Komunikat o sukcesie -->
    @if (session('success'))
      <div class="mb-4 p-4 bg-green-600 text-white rounded">
        {{ session('success') }}
      </div>
    @endif

    <!-- Tabela kategorii -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Nazwa</th>
            <th class="px-4 py-2 border">Akcje</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $category)
          <tr class="hover:bg-gray-100 border-b">
            <td class="px-4 py-2 border text-center">{{ $category->id }}</td>
            <td class="px-4 py-2 border font-semibold">{{ $category->name }}</td>
            <td class="px-4 py-2 border text-center">
              <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition inline-block mb-1">
                Edytuj
              </a>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600 transition">
                  Usuń
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Paginacja -->
    <div class="mt-4">
      {{ $categories->links() }}
    </div>
  </div>
</x-admin-layout>
