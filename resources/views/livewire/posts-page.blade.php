<section id="posts" class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    Posts
                </span>
            </h1>
        </div>

        <!-- Filter and sort bar -->
        <div class="mb-12">
            <div class="flex flex-col sm:flex-row items-center justify-between bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Category filters -->
                <div class="w-full sm:w-auto p-4 sm:p-0">
                    <div class="flex items-center justify-center sm:justify-start sm:border-r border-gray-200 h-full">
                        <div class="sm:pl-8 py-4 sm:pr-8">
                            <p class="text-gray-500 text-sm mb-1 font-medium">Filtry kategorii</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <!-- All categories button -->
                                <button wire:click="$set('category', '')" 
                                    class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                                    {{ $category === '' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Wszystkie
                                </button>
                                
                                <!-- Individual category buttons -->
                                @foreach($categories as $cat)
                                    <button wire:click="$set('category', '{{ $cat->id }}')" 
                                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                                        {{ $category == $cat->id ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $cat->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sorting options -->
                <div class="w-full sm:w-auto p-4 border-t sm:border-t-0 border-gray-200 sm:border-none sm:pr-8">
                    <p class="text-gray-500 text-sm mb-1 font-medium">Sortowanie</p>
                    <div class="flex space-x-3">
                        <!-- Newest first option -->
                        <button wire:click="$set('sortBy', 'newest')" 
                            class="flex items-center px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                            {{ $sortBy === 'newest' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            Najnowsze
                        </button>
                        
                        <!-- Oldest first option -->
                        <button wire:click="$set('sortBy', 'oldest')" 
                            class="flex items-center px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                            {{ $sortBy === 'oldest' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                            </svg>
                            Najstarsze
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts grid section -->
        @if($posts->isEmpty())
            <!-- No posts found message -->
            <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Brak postów</h3>
                <p class="text-gray-600">
                    Nie ma jeszcze żadnych postów. Wróć później!
                </p>
            </div>
        @else
            <!-- Posts grid layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <!-- Individual post card -->
                    <div wire:key="post-{{ $post->id }}" class="bg-white rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl hover:scale-[1.03] overflow-hidden flex flex-col h-full">
                        <!-- Post image with overlay effect -->
                        <div class="flex-none w-full aspect-square relative">
                            <img src="{{ asset('storage/' . ($post->image ?? 'default.jpg')) }}" alt="{{ $post->title }}" 
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Category badge -->
                            @if($post->category)
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold rounded-full shadow-md">
                                        {{ $post->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Post content -->
                        <div class="p-6 flex flex-col flex-grow">
                            <!-- Post title -->
                            <h2 class="text-xl font-bold mb-3">
                                {{ $post->title ?? 'Brak tytułu' }}
                            </h2>
                            
                            <!-- Post metadata -->
                            <div class="flex items-center justify-between mb-3">
                                <!-- Publication date -->
                                <p class="text-gray-600 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ optional($post->created_at)->format('d.m.Y') ?? 'Brak daty' }}
                                </p>
                                
                                <!-- View count -->
                                <div class="flex items-center">
                                    <span class="flex items-center text-gray-600 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $post->views ?? 0 }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Post excerpt -->
                            <p class="text-gray-700 text-base mb-4 break-all">
                                {{ Str::limit($post->content, 100) ?? 'Brak podsumowania' }}
                            </p>

                            <!-- Author info -->
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-gray-300 rounded-full overflow-hidden mr-2">
                                    <img src="{{ asset('storage/' . ($post->user->avatar ?? 'avatars/default.jpg')) }}" 
                                        alt="{{ $post->user->name ?? 'Author' }}" 
                                        class="w-full h-full object-cover">
                                </div>
                                <span class="text-gray-700 text-sm">
                                    {{ $post->user->name ?? 'Nieznany autor' }}
                                </span>
                            </div>

                            <!-- Read more button -->
                            <div class="mt-auto flex justify-center">
                                <a href="{{ route('post.show', ['postId' => $post->id]) }}" 
                                    wire:navigate
                                    wire:prefetch
                                    class="block w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md text-center">
                                    <span wire:loading.remove>Czytaj więcej</span>
                                    <span wire:loading>Ładowanie...</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            <div class="pagination-links">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</section>