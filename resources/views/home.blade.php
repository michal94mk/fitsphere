<x-blog-layout>
    <section class="relative bg-gray-900 text-white h-screen text-center flex flex-col justify-center">
        <div class="absolute inset-0 w-full h-full z-0">
            <video id="video1" class="absolute inset-0 w-full h-full object-cover opacity-20" autoplay loop muted>
                <source src="/videos/video1.mp4" type="video/mp4">
            </video>
        </div>
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold transition duration-500 ease-in-out hover:scale-105">
                Zdrowie & Fitness
            </h1>
            <p class="mt-2 text-lg text-gray-300 opacity-80 transition-all duration-500 hover:opacity-100">
                Trenuj, jedz zdrowo i dbaj o siebie!
            </p>
            <button id="startJourneyButton" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-orange-600 transition duration-500 hover:scale-105">
                Rozpocznij swoją podróż
            </button>
        </div>
    </section>

    <div class="border-t-4 border-gray-900"></div>

    <section id="nextSection" class="bg-gray-300 text-gray-900 py-16 flex items-center justify-center text-center border-t-8 border-b-8 border-gray-900 z-10 pt-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-4">Przygotuj się do działania</h2>
            <p class="mt-4 text-lg">Zaczynamy naszą podróż ku zdrowiu!</p>
        </div>
    </section>

    <div class="border-t-4 border-gray-900"></div>

    <div id="posts" class="max-w-screen-xxl mx-auto p-4">
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-600 text-white rounded">
                {{ session('error') }}
            </div>
        @endif
        
        <h2 class="text-5xl text-center p-20 font-bold mb-6">Najnowsze posty</h2>

        @foreach ($posts as $post)
            <div class="bg-white rounded-lg shadow-lg mb-6 transition-transform duration-300 hover:scale-105 w-full sm:w-3/4 md:w-3/4 lg:w-2/3 mx-auto overflow-hidden flex flex-col md:flex-row">
                <!-- Obrazek -->
                <div class="flex-none md:w-1/3 w-full aspect-square">
                    <img src="{{ asset('storage/' . ($post->image ?? 'default.jpg')) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                </div>
                
                <!-- Sekcja z treścią -->
                <div class="p-4 flex flex-col md:w-2/3">
                    <h2 class="text-2xl font-semibold text-center md:text-left mb-2">
                        {{ $post->title ?? 'Brak tytułu' }}
                    </h2>
                    <p class="text-gray-600 text-sm mb-2">
                        Dodano: {{ optional($post->created_at)->format('d.m.Y') ?? 'Brak daty' }}
                    </p>
                    <p class="text-gray-800 text-base mb-2 break-all">
                        {{ $post->excerpt ?? 'Brak podsumowania' }}
                    </p>
                    <p class="text-gray-800 text-lg">
                        Autor: {{ optional($post->user)->name ?? 'Brak autora' }}
                    </p>
                    <p class="text-gray-800 text-lg">
                        Komentarze: {{ $post->comments_count ?? 0 }}
                    </p>
                    
                    <!-- Przycisk -->
                    <div class="mt-auto flex justify-end">
                        <a href="{{ route('posts.show', $post) }}" class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600 transition-colors">
                            Czytaj więcej
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-6">
            {{ $posts->fragment('posts')->links() }}
        </div>
    </div>

    <div class="border-t border-gray-900"></div>
</x-blog-layout>
