<x-admin-layout>
<div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white p-5">
            <h2 class="text-xl font-bold mb-5">Admin Panel</h2>
            <nav>
                <ul>
                    <li class="mb-3"><a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-blue-700">Dashboard</a></li>
                    <li class="mb-3"><a href="{{ route('admin.users.index') }}" class="block p-2 rounded hover:bg-blue-700">Użytkownicy</a></li>
                    <li class="mb-3"><a href="{{ route('admin.posts.index') }}" class="block p-2 rounded hover:bg-blue-700">Posty</a></li>
                    <li class="mb-3"><a href="{{ route('admin.comments.index') }}" class="block p-2 rounded hover:bg-blue-700">Komentarze</a></li>
                    <li class="mb-3"><a href="{{ route('admin.categories.index') }}" class="block p-2 rounded hover:bg-blue-700">Kategorie</a></li>
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
        </main>
    </div>
</x-admin-layout>
