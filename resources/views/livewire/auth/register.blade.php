<div class="h-screen flex items-center justify-center">
    <div class="min-w-[440px] mx-auto py-8 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Rejestracja</h2>
        <form wire:submit.prevent="register">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Login</label>
                <input type="text" wire:model="name" placeholder="Wpisz login" 
                       class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" wire:model="email" placeholder="Wpisz email" 
                       class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasło</label>
                <input type="password" wire:model="password" placeholder="Wpisz hasło" 
                       class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Potwierdź hasło</label>
                <input type="password" wire:model="password_confirmation" placeholder="Potwierdź hasło" 
                       class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800">
            </div>
            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-orange-600 transition duration-500 text-white font-semibold py-2 px-4 rounded-lg shadow">
                Zarejestruj się
            </button>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Masz już konto? 
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Zaloguj się</a>
                </p>
            </div>
        </form>
    </div>
</div>
