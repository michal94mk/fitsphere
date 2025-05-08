<div>
    <!-- Quick Actions -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-3">Szybkie akcje</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.posts.create') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-indigo-50 transition">
                <div class="p-3 bg-indigo-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Nowy post</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-green-50 transition">
                <div class="p-3 bg-green-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Nowa kategoria</span>
            </a>
            <a href="{{ route('admin.trainers.create') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-blue-50 transition">
                <div class="p-3 bg-blue-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Nowy trener</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-purple-50 transition">
                <div class="p-3 bg-purple-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Nowy użytkownik</span>
            </a>
        </div>
    </div>

    <!-- Statistics cards with improved design -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-3">Statystyki</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow">
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
                <div class="bg-blue-50 px-5 py-2">
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        Zobacz wszystkich 
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow">
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
                <div class="bg-green-50 px-5 py-2">
                    <a href="{{ route('admin.trainers.index') }}" class="text-xs text-green-600 hover:text-green-800 font-medium flex items-center">
                        Zobacz wszystkich
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 mr-4 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Posty</div>
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['posts'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    {{ $stats['publishedPosts'] }} opublikowanych
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                    {{ $stats['draftPosts'] }} szkiców
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 px-5 py-2">
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center">
                        Zobacz wszystkie
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 mr-4 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Komentarze</div>
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['comments'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 px-5 py-2">
                    <a href="{{ route('admin.comments.index') }}" class="text-xs text-yellow-600 hover:text-yellow-800 font-medium flex items-center">
                        Zobacz wszystkie
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Two column layout -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Pending Approvals -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Oczekujący trenerzy</h3>
                @if ($stats['pendingTrainers'] > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $stats['pendingTrainers'] }} oczekujących
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Brak oczekujących
                    </span>
                @endif
            </div>
            <div class="px-4 py-3">
                @if (count($pendingTrainers) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($pendingTrainers as $trainer)
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500">
                                                <span class="text-lg font-medium leading-none text-white">{{ substr($trainer->name, 0, 1) }}</span>
                                            </span>
                                        </div>
                                        <div class="ml-4 truncate">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $trainer->name }}</div>
                                            <div class="text-sm text-gray-500 truncate">{{ $trainer->email }}</div>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 space-x-2">
                                        <button wire:click="approveTrainer({{ $trainer->id }})" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Zatwierdź
                                        </button>
                                        <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Szczegóły
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if ($stats['pendingTrainers'] > count($pendingTrainers))
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.trainers.index') }}?filter=pending" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Zobacz wszystkie ({{ $stats['pendingTrainers'] }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Brak oczekujących trenerów</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Wszystkie zgłoszenia zostały obsłużone.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Draft Posts -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Szkice postów</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $stats['draftPosts'] }} szkiców
                </span>
            </div>
            <div class="px-4 py-3">
                @if (count($draftPosts) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($draftPosts as $post)
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $post->title }}
                                        </p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span>{{ $post->user ? $post->user->name : 'System' }} · </span>
                                            <span>{{ $post->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 space-x-2">
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Edytuj
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if ($stats['draftPosts'] > count($draftPosts))
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.posts.index') }}?status=draft" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Zobacz wszystkie ({{ $stats['draftPosts'] }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Brak szkiców</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Wszystkie posty zostały opublikowane.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Popular Posts -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Popularne posty</h3>
            </div>
            <div class="px-4 py-3">
                @if (count($popularPosts) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($popularPosts as $post)
                            <li class="py-3">
                                <div class="flex items-center">
                                    @if ($post->image)
                                        <div class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden bg-gray-100">
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="h-10 w-10 object-cover">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden bg-indigo-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">{{ $post->title }}</p>
                                            <div class="ml-2 flex items-center text-sm text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                <svg class="flex-shrink-0 h-4 w-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ $post->views()->count() }}
                                            </div>
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs text-gray-500">{{ $post->created_at->format('d.m.Y') }}</span>
                                            <span class="mx-1 text-gray-500">•</span>
                                            <span class="text-xs text-gray-500">{{ $post->comments()->count() }} komentarzy</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4 text-gray-500">Brak danych o popularnych postach</div>
                @endif
            </div>
        </div>

        <!-- Recent Users -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Nowi użytkownicy</h3>
            </div>
            <div class="px-4 py-3">
                @if (count($recentUsers) > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach ($recentUsers as $user)
                            <li class="py-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                        @if ($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="h-10 w-10 object-cover">
                                        @else
                                            <span class="text-lg font-medium text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                                                Edytuj
                                            </a>
                                        </div>
                                        <div class="mt-1 flex items-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $user->role === 'admin' ? 'Administrator' : 'Użytkownik' }}
                                            </span>
                                            <span class="text-xs text-gray-500 ml-2">Dołączył {{ $user->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4 text-gray-500">Brak nowych użytkowników</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="mt-6">
        <livewire:admin.components.activity-log />
    </div>
</div> 