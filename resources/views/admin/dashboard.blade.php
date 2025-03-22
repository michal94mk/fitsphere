<x-admin-layout>
    <div class="flex h-screen">       
        <!-- Main Content -->
        <main class="flex-1 p-5 overflow-y-auto">
            <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded shadow">
                    <h2 class="text-lg font-semibold">Użytkownicy</h2>
                    <p class="text-2xl">{{ $userCount }}</p>
                </div>
                <div class="bg-white p-5 rounded shadow">
                    <h2 class="text-lg font-semibold">Posty</h2>
                    <p class="text-2xl">{{ $postCount }}</p>
                </div>
                <div class="bg-white p-5 rounded shadow">
                    <h2 class="text-lg font-semibold">Komentarze</h2>
                    <p class="text-2xl">{{ $commentCount }}</p>
                </div>
            </div>
        </main>
    </div>
</x-admin-layout>
