<x-admin-layout>
  <div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
        Wróć do listy użytkowników
      </a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-2xl font-bold mb-4">Szczegóły użytkownika: {{ $user->name }}</h2>

      <p><strong>Imię:</strong> {{ $user->name }}</p>
      <p><strong>Email:</strong> {{ $user->email }}</p>
      <p><strong>Rola:</strong> {{ ucfirst($user->role) }}</p>
      <p><strong>Data dodania:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
    </div>
  </div>
</x-admin-layout>
