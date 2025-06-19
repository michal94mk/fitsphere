<section id="posts" class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    {{ __('posts.title') }}
                </span>
            </h1>
            
            <!-- Flash messages -->
            @if (session()->has('error'))
                <div class="mx-auto max-w-3xl mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Filter and sort bar -->
        <div class="mb-12">
            <div class="flex flex-col sm:flex-row items-center justify-between bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Category filters -->
                <div class="w-full sm:w-auto p-4 sm:p-0">
                    <div class="flex items-center justify-center sm:justify-start sm:border-r border-gray-200 h-full">
                        <div class="sm:pl-8 py-4 sm:pr-8">
                            <p class="text-gray-500 text-sm mb-1 font-medium">{{ __('posts.categories') }}</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <!-- All categories button -->
                                <button wire:click="$set('category', '')" 
                                    class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                                    {{ $category === '' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ __('posts.all_categories') }}
                                </button>
                                
                                <!-- Individual category buttons -->
                                @foreach($categories as $cat)
                                    <button wire:click="$set('category', '{{ $cat->id }}')" 
                                        class="px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                                        {{ $category == $cat->id ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $cat->getTranslatedName() }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sorting options -->
                <div class="w-full sm:w-auto p-4 border-t sm:border-t-0 border-gray-200 sm:border-none sm:pr-8">
                    <p class="text-gray-500 text-sm mb-1 font-medium">{{ __('common.sort') }}</p>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <!-- Newest first option -->
                        <button wire:click="$set('sortBy', 'newest')" 
                            class="flex items-center justify-center px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                            {{ $sortBy === 'newest' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                            {{ __('common.newest') }}
                        </button>
                        
                        <!-- Oldest first option -->
                        <button wire:click="$set('sortBy', 'oldest')" 
                            class="flex items-center justify-center px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                            {{ $sortBy === 'oldest' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                            </svg>
                            {{ __('common.oldest') }}
                        </button>
                        
                        <!-- Popular option -->
                        <button wire:click="$set('sortBy', 'popular')" 
                            class="flex items-center justify-center px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                            {{ $sortBy === 'popular' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ __('common.popular') }}
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
                <h3 class="text-xl font-bold text-gray-700 mb-2">{{ __('posts.no_posts') }}</h3>
                <p class="text-gray-600">
                    {{ __('posts.no_posts') }}
                </p>
            </div>
        @else
            <!-- Posts grid layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <!-- Modern post card design -->
                    <div wire:key="post-{{ $post->id }}" class="group relative h-full">
                        <!-- Card with hover effect -->
                        <div class="relative h-full bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col">
                            <!-- Post image with elegant overlay -->
                            <div class="relative overflow-hidden">
                                <!-- Hexagon shape overlay -->
                                <div class="absolute -bottom-12 -right-12 w-24 h-24 bg-blue-50 rotate-45 z-0"></div>
                                
                                <!-- Image container with fixed aspect ratio -->
                                <div class="aspect-[4/3] overflow-hidden">
                                    <img 
                                        src="{{ asset('storage/' . ($post->image ?? 'default.jpg')) }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-full h-full object-cover object-center transform group-hover:scale-105 transition duration-700 ease-in-out"
                                    >
                                </div>
                                
                                <!-- Category badge -->
                                @if($post->category)
                                <div class="absolute top-4 left-4">
                                    <span class="inline-block px-3 py-1 text-xs font-medium bg-blue-600/90 text-white backdrop-blur-sm rounded-md">
                                        {{ $post->category->getTranslatedName() }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Content area -->
                            <div class="flex-1 p-6">
                                <!-- Post title with hover effect -->
                                <h2 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition-colors duration-300">
                                    @if($post->translations->isNotEmpty())
                                        {{ $post->translations->first()->title }}
                                    @else
                                        {{ $post->title ?? __('common.no_title') }}
                                    @endif
                                </h2>
                                
                                <!-- Subtle divider line with gradient -->
                                <div class="w-12 h-1 mt-2 mb-4 bg-gradient-to-r from-blue-500 to-blue-400 rounded"></div>
                                
                                <!-- Post excerpt with elegant typography -->
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    @if($post->translations->isNotEmpty() && $post->translations->first()->excerpt)
                                        {{ Str::limit($post->translations->first()->excerpt, 90) }}
                                    @elseif($post->translations->isNotEmpty())
                                        {{ Str::limit($post->translations->first()->content, 90) }}
                                    @else
                                        {{ Str::limit($post->content, 90) ?? __('common.no_summary') }}
                                    @endif
                                </p>
                                
                                <!-- Author info with avatar -->
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 rounded-full overflow-hidden mr-2 flex-shrink-0">
                                        <img src="{{ $post->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name ?? 'Author') }}" 
                                            alt="{{ $post->user->name ?? 'Author' }}" 
                                            class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-gray-700 text-sm">
                                        {{ $post->user->name ?? __('common.unknown_author') }}
                                    </span>
                                </div>
                                
                                <!-- Post metadata -->
                                <div class="grid grid-cols-3 gap-2 text-sm text-gray-500">
                                    <!-- Publication date -->
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ optional($post->created_at)->format('d.m.Y') ?? __('common.no_date') }}
                                    </div>
                                    
                                    <!-- View count -->
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ number_format($post->view_count) }}
                                    </div>
                                    
                                    <!-- Comments count -->
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        {{ $post->comments_count ?? 0 }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modern view article button -->
                            <div class="border-t border-gray-100">
                                <a 
                                    href="{{ route('post.show', ['postId' => $post->id]) }}"
                                    wire:navigate
                                    wire:prefetch
                                    class="flex items-center justify-between px-6 py-4 text-gray-800 hover:bg-blue-50 transition-colors duration-300 group"
                                >
                                    <span class="font-medium group-hover:text-blue-600 transition-colors duration-300">
                                        <span wire:loading.remove>{{ __('posts.read_more') }}</span>
                                        <span wire:loading>{{ __('common.loading') }}...</span>
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
        @endif
        
        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            <div class="pagination-links">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</section>