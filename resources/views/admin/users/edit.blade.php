<x-admin-layout>
  <div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
        Wróć do listy użytkowników
      </a>
    </div>

    <!-- Użycie Alpine.js do zarządzania stanem pola "role" -->
    <div class="bg-white p-6 rounded-lg shadow-md" x-data="{ role: '{{ old('role', $user->role) }}' }">
      <h2 class="text-2xl font-bold mb-4">Edytuj użytkownika: {{ $user->name }}</h2>

      <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Imię -->
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Imię</label>
          <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
        </div>

        <!-- Rola -->
        <div class="mb-4">
          <label for="role" class="block text-sm font-medium text-gray-700">Rola</label>
          <select id="role" name="role" x-model="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>admin</option>
            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>user</option>
            <option value="trainer" {{ old('role', $user->role) == 'trainer' ? 'selected' : '' }}>trainer</option>
          </select>
        </div>

        <!-- Pola dodatkowe dla trenerów (pokazywane tylko gdy role == 'trainer') -->
        <div x-show="role === 'trainer'">
          <!-- Specjalizacja -->
          <div class="mb-4">
            <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
            <input type="text" id="specialization" name="specialization" value="{{ old('specialization', $user->specialization) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
          </div>

          <!-- Opis -->
          <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Opis</label>
            <textarea id="description" name="description" rows="4" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">{{ old('description', $user->description) }}</textarea>
          </div>

          <!-- Zdjęcie -->
          <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Zdjęcie</label>
            <input type="file" id="image" name="image" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
            @if($user->image)
              <p class="mt-2 text-sm text-gray-600">Aktualne zdjęcie:</p>
              <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="w-16 h-16 object-cover rounded-full">
            @endif
          </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
          Zaktualizuj użytkownika
        </button>
      </form>
    </div>
  </div>
</x-admin-layout>
