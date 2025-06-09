<div class="bg-gradient-to-br from-gray-50 to-gray-100 py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block p-1 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 mb-4">
                <div class="bg-gray-50 rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold mb-4">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    {{ __('profile.your_profile') }}
                </span>
            </h1>
            <p class="text-gray-600 max-w-xl mx-auto">{{ __('profile.manage_profile_info') }}</p>
        </div>

        @if (session()->has('registration_success'))
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-xl shadow-md mb-10 animate-pulse max-w-4xl mx-auto">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-lg font-medium">{{ session('registration_success') }}</span>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl border border-gray-100">
                <div class="h-3 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                <div class="p-8">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('profile.member_since') }}</span>
                            <span class="font-medium text-gray-800">
                                @if(Auth::check())
                                    {{ Auth::user()->created_at->format('d.m.Y') }}
                                @elseif(Auth::guard('trainer')->check())
                                    {{ Auth::guard('trainer')->user()->created_at->format('d.m.Y') }}
                                @else
                                    --
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('profile.role') }}</span>
                            <span class="font-medium text-gray-800">
                                @if(Auth::check())
                                    {{ ucfirst(Auth::user()->role ?? __('profile.user')) }}
                                @elseif(Auth::guard('trainer')->check()) 
                                    {{ __('profile.trainer') }}
                                @else
                                    --
                                @endif
                            </span>
                        </div>
                        @if(Auth::check() && Auth::user()->provider)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">{{ __('profile.login_method') }}</span>
                            <span class="font-medium text-blue-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                {{ ucfirst(Auth::user()->provider) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
                <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                <div class="p-8">
                    <livewire:user.profile.update-user-profile />
                </div>
            </div>

            @if(!Auth::user()->provider)
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
                <div class="h-2 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                <div class="p-8">
                    <livewire:user.profile.update-password />
                </div>
            </div>
            @else
            <div class="bg-blue-50 rounded-2xl border border-blue-200 p-8">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-medium text-blue-900">{{ __('profile.social_login_account') }}</h3>
                        <p class="text-blue-700">{{ __('profile.password_managed_by_provider') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
                <div class="h-2 bg-gradient-to-r from-red-500 to-red-600"></div>
                <div class="p-8">
                    <livewire:user.profile.delete-user-account />
                </div>
            </div>
        </div>
    </div>
</div> 