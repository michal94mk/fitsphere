<x-admin-layout>
<div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white p-5">
            <h2 class="text-xl font-bold mb-5">Admin Panel</h2>
            <nav>
                <ul>
                    <li class="mb-3"><a href="{{ route('admin-dashboard') }}" class="block p-2 rounded hover:bg-blue-700">Dashboard</a></li>
                    <li class="mb-3"><a href="{{ route('users.index') }}" class="block p-2 rounded hover:bg-blue-700">Użytkownicy</a></li>
                    <li class="mb-3"><a href="{{ route('admin.posts.index') }}" class="block p-2 rounded hover:bg-blue-700">Posty</a></li>
                    <li class="mb-3"><a href="{{ route('comments.index') }}" class="block p-2 rounded hover:bg-blue-700">Komentarze</a></li>
                    <li class="mb-3"><a href="{{ route('categories.index') }}" class="block p-2 rounded hover:bg-blue-700">Kategorie</a></li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-5">
            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
            
            <!-- Cards -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded shadow">
                    <h2 class="text-lg font-semibold">Użytkownicy</h2>
                    <p class="text-2xl">123</p>
                </div>
                <div class="bg-white p-5 rounded shadow">
                    <h2 class="text-lg font-semibold">Posty</h2>
                    <p class="text-2xl">321</p>
                </div>
                <div class="bg-white p-5 rounded shadow">
                    <h2 class="text-lg font-semibold">Komentarze</h2>
                    <p class="text-2xl">456</p>
                </div>
            </div>
            
            <!-- Posts Table -->
            <div class="mt-5 bg-white p-5 rounded shadow">
                <h2 class="text-xl font-bold mb-3">Lista Postów</h2>
                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Tytuł</th>
                            <th class="border p-2">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border p-2">1</td>
                            <td class="border p-2">Pierwszy post</td>
                            <td class="border p-2">
                                <a href="#" class="text-blue-500">Pokaż</a> |
                                <a href="#" class="text-yellow-500">Edytuj</a> |
                                <a href="#" class="text-red-500">Usuń</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="border p-2">2</td>
                            <td class="border p-2">Drugi post</td>
                            <td class="border p-2">
                                <a href="#" class="text-blue-500">Pokaż</a> |
                                <a href="#" class="text-yellow-500">Edytuj</a> |
                                <a href="#" class="text-red-500">Usuń</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</x-admin-layout>
