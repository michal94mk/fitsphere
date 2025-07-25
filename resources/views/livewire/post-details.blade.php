<div>
<div class="max-w-4xl mx-auto px-6">
    <div class="bg-white shadow-md rounded-lg mb-10 overflow-hidden">
        <!-- Post Header Section -->
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-5 flex items-center">
            <div class="flex-shrink-0 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-medium text-gray-800">
                @if($translation)
                    {{ $translation->title }}
                @else
                    {{ $post->title }}
                @endif
            </h1>
        </div>

        <!-- Post Meta Information -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200 text-sm text-gray-600">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ $post->user->name }}</span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    @if($post->created_at)
                        {{ $post->created_at->format('d.m.Y') }}
                    @else
                        <span class="italic text-gray-400">{{ __('common.no_date') }}</span>
                    @endif
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ number_format($post->view_count) }} {{ __('common.views') }}</span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                    </svg>
                    <span>{{ app()->getLocale() == 'pl' ? __('common.polish') : __('common.english') }}</span>
                </div>
            </div>
        </div>

        <!-- Post Content -->
        <div class="p-6">
            @if($post->image)
                <div class="w-full flex justify-center mb-6">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="{{ $translation ? $translation->title : $post->title }}" 
                         class="max-w-full h-auto rounded-lg shadow-sm">
                </div>
            @endif

            <div class="text-gray-700 leading-relaxed mb-6">
                @if($translation && $translation->content)
                    {{ $translation->content }}
                @elseif($post->content)
                    {{ $post->content }}
                @else
                    <span class="italic text-gray-400">{{ __('common.no_summary') }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-5 flex items-center">
            <div class="flex-shrink-0 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
            <h2 class="text-xl font-medium text-gray-800">{{ __('common.comments') }}</h2>
                </div>

        <!-- Success or Error Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 m-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @elseif (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 m-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-6">
            <!-- If there are no comments -->
            @if($comments->isEmpty())
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="mt-2 text-gray-500">{{ __('common.no_comments') }}</p>
                </div>
            @else
                <!-- Display each comment -->
                <div class="space-y-4">
                    @foreach($comments as $comment)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="h-10 w-10 rounded-full overflow-hidden">
                                        <img src="{{ $comment->user->profile_photo_url ?? '/images/default-avatar.png' }}" alt="{{ $comment->user->name }}" class="h-10 w-10 object-cover">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <h3 class="text-sm font-medium text-gray-900">
                                                {{ $comment->user->name }}
                                            </h3>
                                            @if($comment->user->role === 'trainer')
                                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ __('common.trainer') }}</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            @if($comment->created_at)
                                                {{ $comment->created_at->format('d.m.Y H:i') }}
                                            @else
                                                <span class="italic">{{ __('common.no_date') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-700 break-words">
                                        {{ $comment->content }}
                                    </div>
                                    @if($comment->belongsToAuthUser())
                                        <div class="mt-2 text-right">
                                            <a href="{{ route('comments.livewire-edit', $comment->id) }}" class="text-xs inline-flex items-center text-blue-500 hover:text-blue-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                {{ __('common.edit') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination for comments -->
                <div class="mt-16 flex justify-center">
                    <div class="pagination-links">
                        {{ $comments->links() }}
                    </div>
                </div>
            @endif

            <!-- Add Comment Section (for authenticated and verified users/trainers) -->
            @if(auth()->check())
                @php
                    $user = auth()->user();
                    $isVerified = $user && $user->email_verified_at;
                @endphp
                
                @if($isVerified)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-700 mb-4">{{ __('common.add_comment') }}</h3>
                        <form wire:submit.prevent="addComment">
                            <div class="relative">
                                <textarea wire:model="newComment" rows="4" required 
                                    class="w-full p-3 border border-gray-300 rounded-md resize-none focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" 
                                    placeholder="{{ __('common.write_comment') }}"></textarea>
                                @error('newComment') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    {{ __('common.add_comment') }}
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Prompt for email verification -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-md text-center">
                            <div class="flex items-center justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-yellow-800 font-medium">{{ __('common.verify_email_to_comment') }}</span>
                            </div>
                            <a href="{{ route('verification.notice') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ __('profile.resend_link') }}
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <!-- Prompt for users to log in if they are not authenticated -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-gray-50 p-4 rounded-md text-center">
                        <p class="text-gray-700 mb-3">{{ __('common.login_to_comment') }}</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" />
                            </svg>
                            {{ __('common.login') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>