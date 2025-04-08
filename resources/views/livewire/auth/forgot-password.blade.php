<div class="h-screen flex items-center justify-center">
    <div class="min-w-[440px] mx-auto py-8 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Zapomniałeś hasła?</h2>

        @if (session()->has('message'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded text-sm text-center">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetLink">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input 
                    type="email" 
                    wire:model="email" 
                    placeholder="Wpisz swój email"
                    class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800">
                @error('email') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-blue-500 hover:bg-orange-600 transition duration-500 text-white font-semibold py-2 px-4 rounded-lg shadow">
                Wyślij link resetujący
            </button>
        </form>
    </div>
</div>