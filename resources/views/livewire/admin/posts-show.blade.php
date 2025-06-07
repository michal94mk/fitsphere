<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.post_details') }}</h2>
            <p class="text-gray-600">{{ __('admin.view_post_information') }}</p>
        </div>
        <div>
            <x-admin.form-button style="secondary" :href="route('admin.posts.index')" navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('admin.back_to_list') }}
            </x-admin.form-button>
        </div>
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-start">
                @if($post->image)
                    <div class="flex-shrink-0 mr-4">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="h-20 w-20 rounded-lg object-cover ring-2 ring-gray-200">
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $post->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $post->slug }}</p>
                    <div class="mt-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.author') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $post->user->name ?? __('admin.unknown') }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.post_category') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($post->category)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $post->category->name }}
                            </span>
                        @else
                            <span class="text-gray-500">{{ __('admin.none') }}</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.post_status') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $post->status === 'published' ? __('admin.status_published') : __('admin.status_draft') }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.created_at') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $post->created_at->format('d.m.Y H:i') }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.last_updated') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $post->updated_at->format('d.m.Y H:i') }}
                    </dd>
                </div>
                @if($post->excerpt)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.post_excerpt') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $post->excerpt }}</dd>
                </div>
                @endif
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.post_statistics') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div>{{ __('admin.comments') }}: <span class="font-semibold">{{ $post->comments->count() }}</span></div>
                            <div>{{ __('admin.translations') }}: <span class="font-semibold">{{ $post->translations->count() }}</span></div>
                        </div>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('admin.post_content') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="prose prose-sm max-w-none bg-gray-50 p-4 rounded-lg">
                            {!! nl2br(e(Str::limit($post->content, 500))) !!}
                            @if(strlen($post->content) > 500)
                                <div class="mt-2 text-gray-500 text-xs">
                                    {{ __('admin.content_truncated') }}
                                </div>
                            @endif
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
