<x-admin-layout>
  <div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
        Wróć do listy użytkowników
      </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-4">Edytuj użytkownika: {{ $user->name }}</h2>

      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Imię</label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
          <label for="role" class="block text-sm font-medium text-gray-700">Rola</label>
          <select id="role" name="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Użytkownik</option>
          </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
          Zaktualizuj użytkownika
        </button>
      </form>
    </div>
  </div>
</x-admin-layout>
