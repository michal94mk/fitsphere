<section id="posts" class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl text-center font-bold mb-12">Posty</h2>

        <!-- Siatka 3 kolumnowa -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <div wire:key="post-{{ $post->id }}" class="bg-white rounded-lg shadow-lg transition-transform duration-300 hover:scale-105 overflow-hidden flex flex-col">
                    <!-- Obrazek -->
                    <div class="flex-none w-full aspect-square">
                        <img src="{{ asset('storage/' . ($post->image ?? 'default.jpg')) }}" alt="{{ $post->title }}" 
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Treść -->
                    <div class="p-6 flex flex-col">
                        <h2 class="text-xl font-semibold text-center md:text-left mb-2">
                            {{ $post->title ?? 'Brak tytułu' }}
                        </h2>
                        <p class="text-gray-600 text-sm mb-2">
                            Dodano: {{ optional($post->created_at)->format('d.m.Y') ?? 'Brak daty' }}
                        </p>
                        <p class="text-gray-800 text-base mb-2 break-all">
                            {{ Str::limit($post->content, 100) ?? 'Brak podsumowania' }}
                        </p>

                        <!-- Przycisk -->
                        <div class="mt-auto flex justify-center">
                            <button wire:click="goToPost({{ $post->id }})" 
                                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-orange-600 transition duration-500 hover:scale-105">
                                Czytaj więcej
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
