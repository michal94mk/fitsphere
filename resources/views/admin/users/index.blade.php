<x-admin-layout>
  <div class="container mx-auto p-6">
    <!-- Nagłówek z tytułem i przyciskami -->
    <div class="flex flex-col sm:items-start mb-4">
      <h1 class="text-2xl font-bold text-center sm:text-left mb-4">
        Lista użytkowników ({{ ucfirst($role) }})
      </h1>

      <div class="flex flex-row space-x-2 justify-center sm:justify-start">
        <a href="{{ route('admin.users.dashboard') }}" 
           class="bg-gray-500 text-white px-4 py-2 h-10 rounded-md hover:bg-gray-600 transition flex items-center justify-center">
          Cofnij
        </a>
      </div>
    </div>

    <!-- Komunikat o sukcesie -->
    @if (session('success'))
      <div class="mb-4 p-4 bg-green-600 text-white rounded">
        {{ session('success') }}
      </div>
    @endif

    <!-- Tabela użytkowników -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Imię</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Rola</th>

            @if ($role === 'trainer')
              <th class="px-4 py-2 border">Specjalizacja</th>
              <th class="px-4 py-2 border">Opis</th>
              <th class="px-4 py-2 border">Zdjęcie</th>
            @endif

            <th class="px-4 py-2 border">Akcje</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr class="hover:bg-gray-100 border-b">
            <td class="px-4 py-2 border text-center">{{ $user->id }}</td>
            <td class="px-4 py-2 border font-semibold">{{ $user->name }}</td>
            <td class="px-4 py-2 border">{{ $user->email }}</td>
            <td class="px-4 py-2 border text-center">
              <span class="px-2 py-1 text-xs font-semibold rounded-md 
                          {{ $user->role === 'admin' ? 'bg-red-500' : ($user->role === 'trainer' ? 'bg-blue-500' : 'bg-gray-400') }} text-white">
                {{ ucfirst($user->role) }}
              </span>
            </td>

            @if ($role === 'trainer')
              <td class="px-4 py-2 border">{{ $user->specialization ?? 'Brak' }}</td>
              <td class="px-4 py-2 border text-sm">{{ Str::limit($user->description, 50, '...') }}</td>
              <td class="px-4 py-2 border text-center">
                @if($user->image)
                  <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="w-16 h-auto rounded">
                @else
                  <span class="text-gray-500 text-sm">Brak zdjęcia</span>
                @endif
              </td>
            @endif

            <td class="px-4 py-2 border text-center">
              <a href="{{ route('admin.users.edit', $user) }}" 
                 class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition inline-block mb-1">
                Edytuj
              </a>
              <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
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
      {{ $users->links() }}
    </div>
  </div>
</x-admin-layout>
