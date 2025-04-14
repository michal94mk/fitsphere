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
                    Twój profil
                </span>
            </h1>
            <p class="text-gray-600 max-w-xl mx-auto">Zarządzaj swoimi ustawieniami, danymi osobowymi i preferencjami bezpieczeństwa</p>
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
                            <span class="text-gray-500">Członek od</span>
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
                            <span class="text-gray-500">Rola</span>
                            <span class="font-medium text-gray-800">
                                @if(Auth::check())
                                    {{ ucfirst(Auth::user()->role ?? 'użytkownik') }}
                                @elseif(Auth::guard('trainer')->check()) 
                                    Trener
                                @else
                                    --
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-10">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
                <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                <div class="p-8">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
                <div class="h-2 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                <div class="p-8">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 transform transition-all duration-300 hover:shadow-2xl">
                <div class="h-2 bg-gradient-to-r from-red-500 to-red-600"></div>
                <div class="p-8">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</div> 