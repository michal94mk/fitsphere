<div>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 pt-4 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        @if($trainer)
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <!-- Nagłówek profilu -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Profil trenera</h1>
                </div>
                
                <!-- Dane trenera -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Zdjęcie trenera -->
                        <div class="w-full md:w-1/3">
                            <div class="aspect-w-1 aspect-h-1 rounded-xl overflow-hidden bg-gray-200">
                                @if(isset($trainer->image) && $trainer->image)
                                    <img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Rating -->
                            <div class="mt-4 flex items-center justify-center">
                                <div class="flex space-x-1">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="w-5 h-5 {{ isset($trainer->rating) && $i < $trainer->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-gray-600 text-sm ml-2">{{ isset($trainer->role) ? $trainer->role : 'Użytkownik' }}</span>
                            </div>
                            
                            <!-- Social media links -->
                            <div class="mt-6 flex justify-center space-x-4">
                                @if(isset($trainer->twitter_link) && $trainer->twitter_link)
                                <a href="{{ $trainer->twitter_link }}" class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                @endif
                                @if(isset($trainer->instagram_link) && $trainer->instagram_link)
                                <a href="{{ $trainer->instagram_link }}" class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                                @endif
                                @if(isset($trainer->facebook_link) && $trainer->facebook_link)
                                <a href="{{ $trainer->facebook_link }}" class="text-gray-400 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                            
                            <!-- Przycisk rezerwacji -->
                            <div class="mt-6">
                                <a href="{{ route('reservation.create', $trainer->id) }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Zarezerwuj termin
                                </a>
                                
                                <a href="{{ route('user.reservations') }}" class="w-full inline-flex justify-center items-center px-6 py-3 mt-2 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Moje rezerwacje
                                </a>
                            </div>
                        </div>
                        
                        <!-- Informacje o trenerze -->
                        <div class="w-full md:w-2/3">
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $trainer->name }}</h2>
                            <p class="text-lg text-blue-600 font-medium mb-4">
                                {{ isset($trainer->specialization) ? $trainer->specialization : 'Profil użytkownika' }}
                            </p>
                            
                            <div class="prose prose-blue max-w-none">
                                <div class="text-gray-700 mb-6">
                                    @if(isset($trainer->bio) && $trainer->bio)
                                        {!! nl2br(e($trainer->bio)) !!}
                                    @else
                                        <p class="italic text-gray-500">Brak informacji biograficznych.</p>
                                    @endif
                                </div>
                                
                                @if(isset($trainer->specialties) && $trainer->specialties)
                                <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-2">Specjalizacje</h3>
                                <div class="flex flex-wrap gap-2 mb-6">
                                    @foreach(explode(',', $trainer->specialties) as $specialty)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                            {{ trim($specialty) }}
                                        </span>
                                    @endforeach
                                </div>
                                @endif
                                
                                <!-- Dane kontaktowe -->
                                @if(isset($trainer->email) && $trainer->email)
                                    <h3 class="text-xl font-semibold text-gray-800 mt-6 mb-2">Kontakt</h3>
                                    <ul class="space-y-2">
                                        <li class="flex items-center text-gray-700">
                                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $trainer->email }}
                                        </li>
                                        @if(isset($trainer->phone) && $trainer->phone)
                                            <li class="flex items-center text-gray-700">
                                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $trainer->phone }}
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-gray-600">Nie znaleziono profilu wybranego trenera.</p>
            </div>
        @endif
    </div>
</div>
</div>