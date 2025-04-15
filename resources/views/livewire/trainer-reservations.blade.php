<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Zarządzanie rezerwacjami</h1>
    
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if ($reservations->isEmpty())
            <div class="px-4 py-5 sm:p-6 text-center">
                <p class="text-gray-500">Nie masz jeszcze żadnych rezerwacji.</p>
            </div>
        @else
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Użytkownik
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Godzina
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Uwagi
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Akcje
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $reservation->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 ml-2">
                                                        {{ $reservation->user->email }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $reservation->date->format('d.m.Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($reservation->status == 'confirmed') bg-green-100 text-green-800 
                                                    @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800 
                                                    @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800 
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @if($reservation->status == 'confirmed') Potwierdzona
                                                    @elseif($reservation->status == 'pending') Oczekująca
                                                    @elseif($reservation->status == 'cancelled') Anulowana
                                                    @elseif($reservation->status == 'completed') Zakończona
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $reservation->notes ?? '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($reservation->status == 'pending')
                                                    <button wire:click="confirmReservation({{ $reservation->id }})" class="text-green-600 hover:text-green-900 mr-3">
                                                        Potwierdź
                                                    </button>
                                                    <button wire:click="cancelReservation({{ $reservation->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')">
                                                        Anuluj
                                                    </button>
                                                @elseif($reservation->status == 'confirmed')
                                                    <button wire:click="completeReservation({{ $reservation->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                                        Oznacz jako zakończoną
                                                    </button>
                                                    <button wire:click="cancelReservation({{ $reservation->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')">
                                                        Anuluj
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
