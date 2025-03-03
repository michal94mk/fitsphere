<x-admin-layout>
  <div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
        Wróć do listy użytkowników
      </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-4">Dodaj nowego użytkownika</h2>

      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Imię</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Hasło</label>
          <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Potwierdź hasło</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
          <label for="role" class="block text-sm font-medium text-gray-700">Rola</label>
          <select id="role" name="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Użytkownik</option>
          </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
          Dodaj użytkownika
        </button>
      </form>
    </div>
  </div>
</x-admin-layout>
