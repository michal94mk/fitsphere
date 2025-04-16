<div>
    <section class="relative bg-gradient-to-br from-gray-900 to-gray-800 text-white h-screen text-center flex flex-col justify-center overflow-hidden">
        <!-- Background video -->
        <div class="absolute inset-0 w-full h-full z-0">
            <video id="video1" class="absolute inset-0 w-full h-full object-cover opacity-20" autoplay loop muted>
                <source src="/videos/video1.mp4" type="video/mp4">
                <source src="/videos/video1.webm" type="video/webm">
            </video>
        </div>

        <!-- Hero content -->
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl font-extrabold mb-4 transition duration-500 ease-in-out hover:scale-105">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-blue-600">
                    Zdrowie & Fitness
                </span>
            </h1>
            <p class="mt-2 text-xl text-gray-300 opacity-90 transition-all duration-500 hover:opacity-100 max-w-2xl mx-auto">
                Trenuj, jedz zdrowo i dbaj o siebie!
            </p>
            
            <!-- Button to start the journey -->
            <button 
                x-data="{ startJourney() { 
                    document.getElementById('posts').scrollIntoView({ behavior: 'smooth' });
                } }"
                @click="startJourney()" 
                class="mt-8 px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full hover:from-blue-600 hover:to-blue-700 transition duration-500 hover:scale-105 shadow-lg">
                Rozpocznij swoją podróż
            </button>
        </div>
    </section>

    <!-- Quick Access Section - only for authenticated users -->
    @if(Auth::check() || Auth::guard('trainer')->check())
    <section class="bg-blue-50 py-12 border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-center text-blue-800 mb-8">Szybki dostęp</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(Auth::check())
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Moje rezerwacje</h4>
                    <p class="text-gray-600 mb-4">Sprawdź swoje zaplanowane treningi</p>
                    <a href="{{ route('user.reservations') }}" wire:navigate class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Przejdź do rezerwacji
                    </a>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Znajdź trenera</h4>
                    <p class="text-gray-600 mb-4">Przeglądaj dostępnych trenerów i umów się na trening</p>
                    <a href="{{ route('trainers.list') }}" wire:navigate class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Znajdź trenera
                    </a>
                </div>
                @endif
                
                @if(Auth::guard('trainer')->check())
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Panel Trenera</h4>
                    <p class="text-gray-600 mb-4">Zarządzaj swoimi rezerwacjami i profilem</p>
                    <a href="{{ route('trainer.reservations') }}" wire:navigate class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Przejdź do panelu
                    </a>
                </div>
                @endif
                
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Artykuły i porady</h4>
                    <p class="text-gray-600 mb-4">Przeczytaj najnowsze artykuły o zdrowiu i fitnessie</p>
                    <a href="{{ route('posts.list') }}" wire:navigate class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Przeglądaj artykuły
                    </a>
                </div>
                
                <!-- Nowe funkcjonalności dietetyczne -->
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Kalkulator Diety</h4>
                    <p class="text-gray-600 mb-4">Oblicz swoje zapotrzebowanie kaloryczne i makroskładniki</p>
                    <a href="{{ route('nutrition-calculator') }}" wire:navigate class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Wypróbuj kalkulator
                    </a>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center transform transition-all duration-300 hover:shadow-lg hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Planer Posiłków</h4>
                    <p class="text-gray-600 mb-4">Stwórz spersonalizowany plan posiłków na podstawie swojego profilu</p>
                    <a href="{{ route('meal-planner') }}" wire:navigate class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Zaplanuj posiłki
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Posts Section -->
    <section id="posts" class="bg-gradient-to-br from-gray-50 to-gray-100 py-20 border-t-8 border-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl text-center font-bold mb-16">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    Najnowsze posty
                </span>
            </h2>

            <!-- Loop through posts -->
            @foreach ($posts as $post)
                <div wire:key="post-{{ $post->id }}" class="bg-white rounded-2xl shadow-xl mb-8 transition-all duration-300 hover:shadow-2xl hover:scale-[1.02] overflow-hidden flex flex-col md:flex-row max-w-4xl mx-auto">
                    <!-- Post image -->
                    <div class="flex-none md:w-1/3 w-full aspect-square">
                        <img src="{{ asset('storage/' . ($post->image ?? 'default.jpg')) }}" alt="{{ $post->title }}" 
                            class="w-full h-full object-cover">
                    </div>

                    <!-- Post content -->
                    <div class="p-8 flex flex-col md:w-2/3">
                        <h2 class="text-2xl font-bold text-center md:text-left mb-2">
                            {{ $post->title ?? 'Brak tytułu' }}
                        </h2>
                        <p class="text-gray-600 text-sm mb-3">
                            Added: {{ optional($post->created_at)->format('d.m.Y') ?? 'No date' }}
                        </p>
                        <p class="text-gray-700 text-base mb-4 break-all">
                            {{ $post->excerpt ?? 'No excerpt' }}
                        </p>
                        <div class="flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="text-gray-700">
                                Author: {{ optional($post->user)->name ?? 'No author' }}
                            </p>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <p class="text-gray-700">
                                Comments: {{ $post->comments_count ?? 0 }}
                            </p>
                        </div>

                        <!-- Read more button -->
                        <div class="mt-auto flex justify-end">
                        <a href="{{ route('post.show', ['postId' => $post->id]) }}" wire:navigate>
                            Read more
                        </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>