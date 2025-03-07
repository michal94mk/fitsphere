<x-admin-layout>
  <div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="flex space-x-2">
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
          Cofnij
        </a>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
          Dodaj nowego użytkownika
        </a>
      </div>
      <h1 class="text-2xl font-bold text-center flex-grow">Lista użytkowników</h1>
      <div class="w-32"></div>
    </div>

    @if (session('success'))
      <div class="mb-4 p-4 bg-green-600 text-white rounded">
        {{ session('success') }}
      </div>
    @endif

    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-200">
          <tr class="text-left">
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Imię</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Rola</th>
            <th class="px-4 py-2 border">Data dodania</th>
            <th class="px-4 py-2 border">Akcje</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr class="hover:bg-gray-100 border-b">
            <td class="px-4 py-2 border text-center">{{ $user->id }}</td>
            <td class="px-4 py-2 border font-semibold">{{ $user->name }}</td>
            <td class="px-4 py-2 border">{{ $user->email }}</td>
            <td class="px-4 py-2 border">{{ ucfirst($user->role) }}</td>
            <td class="px-4 py-2 border text-center">{{ $user->created_at->format('Y-m-d') }}</td>
            <td class="px-4 py-2 border text-center">
              <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600 transition inline-block mb-1">
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
                    <p class="mb-4">Czy na pewno chcesz usunąć tego użytkownika?</p>
                    <div class="flex justify-end space-x-2">
                      <button type="button" x-on:click="open = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Anuluj
                      </button>
                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
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
      {{ $users->links() }}
    </div>
  </div>
</x-admin-layout>
