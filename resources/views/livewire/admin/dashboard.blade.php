<div>
    <!-- Statistics cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 mr-4 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Użytkownicy</div>
                        <div class="text-2xl font-semibold text-gray-900">{{ $stats['users'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 mr-4 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Trenerzy</div>
                        <div class="text-2xl font-semibold text-gray-900">{{ $stats['trainers'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 mr-4 bg-purple-100 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Artykuły</div>
                        <div class="text-2xl font-semibold text-gray-900">{{ $stats['posts'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 p-3 mr-4 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Rezerwacje</div>
                        <div class="text-2xl font-semibold text-gray-900">{{ $stats['bookings'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Two column layout -->
    <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
        <!-- Trenerzy do zatwierdzenia -->
        @if(count($pendingTrainers) > 0)
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-5 border-b sm:px-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Trenerzy oczekujący na zatwierdzenie</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $stats['pendingTrainers'] }}
                    </span>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Trener</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Akcje</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pendingTrainers as $trainer)
                                <tr>
                                    <td class="py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                @if($trainer->image)
                                                    <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ $trainer->name }}</div>
                                                <div class="text-gray-500">{{ $trainer->specialization ?? 'Brak specjalizacji' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        {{ $trainer->email }}
                                    </td>
                                    <td class="px-3 py-4 text-sm whitespace-nowrap">
                                        <button wire:click="approveTrainer({{ $trainer->id }})" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Zatwierdź
                                        </button>
                                        <a href="{{ route('admin.trainers.show', $trainer->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-3">
                                            Zobacz
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Wszyscy trenerzy -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-5 border-b sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Trenerzy</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if (count($trainerUsers) > 0)
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Trener</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($trainerUsers as $user)
                                    <tr>
                                        <td class="py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10">
                                                    <img class="w-10 h-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500">
                                            {{ $user->email }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-4 py-8 text-center text-gray-500">
                        Brak trenerów.
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent users -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-5 border-b sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Nowi użytkownicy</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                @if (count($recentUsers) > 0)
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Użytkownik</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data dołączenia</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Rola</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($recentUsers as $user)
                                    <tr>
                                        <td class="py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-8 h-8">
                                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                </div>
                                                <div class="ml-3">
                                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500">
                                            {{ $user->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td class="px-3 py-4 text-sm">
                                            <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full
                                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $user->role === 'user' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-4 py-8 text-center text-gray-500">
                        Brak nowych użytkowników.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 