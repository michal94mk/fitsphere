<div>
    <!-- Hero section with background video -->
    <section class="relative bg-gray-900 text-white h-screen text-center flex flex-col justify-center">
        <!-- Background video -->
        <div class="absolute inset-0 w-full h-full z-0">
            <video id="video1" class="absolute inset-0 w-full h-full object-cover opacity-20" autoplay loop muted>
                <source src="/videos/video1.mp4" type="video/mp4">
                <source src="/videos/video1.webm" type="video/webm">
            </video>
        </div>

        <!-- Hero content -->
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold transition duration-500 ease-in-out hover:scale-105">
                Zdrowie & Fitness
            </h1>
            <p class="mt-2 text-lg text-gray-300 opacity-80 transition-all duration-500 hover:opacity-100">
                Trenuj, jedz zdrowo i dbaj o siebie!
            </p>
            
            <!-- Button to start the journey -->
            <button 
                x-data="{ startJourney() { 
                    document.getElementById('posts').scrollIntoView({ behavior: 'smooth' });
                } }"
                @click="startJourney()" 
                class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-orange-600 transition duration-500 hover:scale-105">
                Rozpocznij swoją podróż
            </button>
        </div>
    </section>

    <!-- Posts section -->
    <section id="posts" class="bg-gray-100 py-16 border-t-8 border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl text-center font-bold mb-12">Najnowsze posty</h2>

            <!-- Loop through posts -->
            @foreach ($posts as $post)
                <div wire:key="post-{{ $post->id }}" class="bg-white rounded-lg shadow-lg mb-6 transition-transform duration-300 hover:scale-105 overflow-hidden flex flex-col md:flex-row max-w-4xl mx-auto">
                    <!-- Post image -->
                    <div class="flex-none md:w-1/3 w-full aspect-square">
                        <img src="{{ asset('storage/' . ($post->image ?? 'default.jpg')) }}" alt="{{ $post->title }}" 
                            class="w-full h-full object-cover">
                    </div>

                    <!-- Post content -->
                    <div class="p-6 flex flex-col md:w-2/3">
                        <h2 class="text-2xl font-semibold text-center md:text-left mb-2">
                            {{ $post->title ?? 'Brak tytułu' }}
                        </h2>
                        <p class="text-gray-600 text-sm mb-2">
                            Added: {{ optional($post->created_at)->format('d.m.Y') ?? 'No date' }}
                        </p>
                        <p class="text-gray-800 text-base mb-2 break-all">
                            {{ $post->excerpt ?? 'No excerpt' }}
                        </p>
                        <p class="text-gray-800 text-lg">
                            Author: {{ optional($post->user)->name ?? 'No author' }}
                        </p>
                        <p class="text-gray-800 text-lg">
                            Comments: {{ $post->comments_count ?? 0 }}
                        </p>

                        <!-- Read more button -->
                        <div class="mt-auto flex justify-end">
                            <button wire:click="goToPost({{ $post->id }})" 
                                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-orange-600 transition duration-500 hover:scale-105">
                                Read more
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
