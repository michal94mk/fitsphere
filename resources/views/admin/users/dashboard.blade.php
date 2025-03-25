<x-admin-layout>
  <div class="container mx-auto p-6">
    <!-- Przyciski akcji -->
    <div class="flex flex-col md:flex-row items-center justify-center space-y-2 md:space-y-0 md:space-x-2 mb-4">
      <a href="{{ route('admin.users.dashboard') }}" 
         class="bg-gray-500 text-white px-4 py-2 h-10 rounded-md hover:bg-gray-600 transition flex items-center justify-center whitespace-nowrap">
        Cofnij
      </a>
      <a href="{{ route('admin.users.create') }}" 
         class="bg-blue-500 text-white px-4 py-2 h-10 rounded-md hover:bg-blue-600 transition flex items-center justify-center whitespace-nowrap">
        Dodaj nowego użytkownika
      </a>
    </div>
    
    <!-- Kafelki nawigacyjne -->
    <div class="flex justify-around mb-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
           class="bg-red-500 text-white p-6 rounded-lg text-center font-bold hover:bg-red-600 transition">
          Administratorzy
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'trainer']) }}" 
           class="bg-yellow-500 text-white p-6 rounded-lg text-center font-bold hover:bg-yellow-600 transition">
          Trenerzy
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'user']) }}" 
           class="bg-blue-500 text-white p-6 rounded-lg text-center font-bold hover:bg-blue-600 transition">
          Użytkownicy
        </a>
      </div>
    </div>
  </div>
</x-admin-layout>
