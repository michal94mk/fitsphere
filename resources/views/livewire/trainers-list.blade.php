<section class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    Trainers
                </span>
            </h1>
            <!-- Flash messages -->
            @if (session()->has('error'))
                <div class="mx-auto max-w-3xl mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Introduction text -->
            <p class="text-xl text-gray-700 leading-relaxed mt-4 max-w-3xl mx-auto">
                Nasz zespół składa się z entuzjastów zdrowia, dietetyków i ekspertów, którzy dostarczają wysokiej jakości treści.
            </p>
        </div>

        <!-- Trainers section -->
        <div class="mt-20">
            <!-- Section title with decorative elements -->
            <div class="flex items-center justify-center mb-12">
                <div class="h-0.5 bg-gray-200 w-16 mr-4"></div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    Poznaj Naszych Trenerów
                </h2>
                <div class="h-0.5 bg-gray-200 w-16 ml-4"></div>
            </div>
            
            <!-- Trainers grid layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($trainers as $trainer)
                    <!-- Modern, clean trainer card -->
                    <div class="group relative h-full">
                        <!-- Card with hover effect -->
                        <div class="relative h-full bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col">
                            <!-- Trainer image with elegant overlay -->
                            <div class="relative overflow-hidden">
                                <!-- Hexagon shape overlay -->
                                <div class="absolute -bottom-12 -right-12 w-24 h-24 bg-blue-50 rotate-45 z-0"></div>
                                
                                <!-- Image container with fixed aspect ratio -->
                                <div class="aspect-[4/3] overflow-hidden">
                                    <img 
                                        src="{{ asset('storage/' . $trainer->image) }}" 
                                        alt="{{ $trainer->name }}" 
                                        class="w-full h-full object-cover object-center transform group-hover:scale-105 transition duration-700 ease-in-out"
                                    >
                                </div>
                                
                                <!-- Specialization tag -->
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block px-3 py-1 text-xs font-medium bg-blue-600/90 text-white backdrop-blur-sm rounded-md">
                                        {{ $trainer->specialization }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Content area -->
                            <div class="flex-1 p-6">
                                <!-- Trainer name -->
                                <h3 class="font-bold text-xl text-gray-800 group-hover:text-blue-600 transition-colors duration-300">
                                    {{ $trainer->name }}
                                </h3>
                                
                                <!-- Subtle divider line with gradient -->
                                <div class="w-12 h-1 mt-2 mb-4 bg-gradient-to-r from-blue-500 to-blue-400 rounded"></div>
                                
                                <!-- Bio with elegant typography -->
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ Str::limit($trainer->bio, 90) }}
                                </p>
                                
                                <!-- Experience & credentials -->
                                @if(isset($trainer->experience))
                                <div class="mt-4 flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $trainer->experience }} lat doświadczenia</span>
                                </div>
                                @endif
                                
                                <!-- Social links with clean icons -->
                                <div class="mt-4 flex space-x-3">
                                    @if(isset($trainer->twitter_link) && $trainer->twitter_link)
                                    <a href="{{ $trainer->twitter_link }}" class="text-gray-400 hover:text-blue-500 transition-colors duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                        </svg>
                                    </a>
                                    @endif
                                    @if(isset($trainer->instagram_link) && $trainer->instagram_link)
                                    <a href="{{ $trainer->instagram_link }}" class="text-gray-400 hover:text-blue-500 transition-colors duration-300">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Modern view profile button -->
                            <div class="border-t border-gray-100">
                                <a 
                                    href="{{ route('trainer.show', ['trainerId' => $trainer->id]) }}"
                                    wire:navigate
                                    wire:prefetch
                                    class="flex items-center justify-between px-6 py-4 text-gray-800 hover:bg-blue-50 transition-colors duration-300 group"
                                >
                                    <span class="font-medium group-hover:text-blue-600 transition-colors duration-300">
                                        <span wire:loading.remove>Zobacz profil</span>
                                        <span wire:loading>Ładowanie...</span>
                                    </span>
                                    <svg class="w-5 h-5 text-blue-500 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Custom styled pagination -->
            <div class="mt-16 flex justify-center">
                <div class="pagination-links">
                    {{ $trainers->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
