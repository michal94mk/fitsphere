<x-slot name="header">
    Zarządzanie trenerami
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Header with title and buttons -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Lista trenerów</h2>
                <p class="text-gray-600">Zarządzaj trenerami w systemie</p>
            </div>
            <a href="{{ route('admin.trainers.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Dodaj trenera
            </a>
        </div>

        <!-- Search and filters -->
        <div class="bg-white shadow overflow-hidden rounded-lg">
            <div class="p-4 border-b flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="relative w-full md:w-1/2 mb-4 md:mb-0">
                    <input
                        type="text"
                        wire:model.debounce.300ms="search"
                        placeholder="Szukaj trenerów..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                    />
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="w-full md:w-1/3">
                    <select 
                        wire:model="status" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    >
                        <option value="">Wszyscy trenerzy</option>
                        <option value="approved">Zatwierdzeni</option>
                        <option value="pending">Oczekujący na zatwierdzenie</option>
                    </select>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Zdjęcie
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Imię i Nazwisko
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Specjalizacja
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Akcje
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($trainers as $trainer)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($trainer->image)
                                        <img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $trainer->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $trainer->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $trainer->specialization ?? 'Brak specjalizacji' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($trainer->is_approved)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Zatwierdzony
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Oczekujący
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.trainers.show', $trainer->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        Zobacz
                                    </a>
                                    <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                        Edytuj
                                    </a>
                                    @if(!$trainer->is_approved)
                                        <button wire:click="approveTrainer({{ $trainer->id }})" class="text-green-600 hover:text-green-900 mr-3">
                                            Zatwierdź
                                        </button>
                                    @endif
                                    <button wire:click="deleteTrainer({{ $trainer->id }})" wire:confirm="Czy na pewno chcesz usunąć tego trenera?" class="text-red-600 hover:text-red-900">
                                        Usuń
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Nie znaleziono trenerów.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-t">
                {{ $trainers->links() }}
            </div>
        </div>
    </div>
</div> 