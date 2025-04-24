<div>
    <div class="container mx-auto p-6">
        <!-- Nagłówek z przyciskami i tytułem -->
        <div class="flex items-center justify-between mb-4">
            <!-- Lewa strona: przyciski -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.posts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                    Cofnij
                </a>
                <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    Dodaj nowy post
                </a>
            </div>
            <!-- Środkowa część: tytuł -->
            <h1 class="text-2xl font-bold text-center flex-grow">
                Edytuj post
            </h1>
            <!-- Prawa strona: pusta, żeby wyśrodkować tytuł -->
            <div class="w-32"></div>
        </div>

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if (!$dataLoaded)
            <div class="text-center py-8">
                <div class="spinner-border text-blue-500" role="status">
                    <span class="sr-only">Ładowanie...</span>
                </div>
                <p class="mt-2 text-gray-600">Ładowanie danych...</p>
            </div>
        @else
            <!-- Formularz edycji posta -->
            <form wire:submit.prevent="update" enctype="multipart/form-data">
                <div class="space-y-4 bg-white p-6 rounded-lg shadow">
                    <!-- Tytuł -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Tytuł</label>
                        <input type="text" id="title" wire:model="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug z przyciskiem generowania -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                        <div class="flex space-x-2">
                            <input type="text" id="slug" wire:model.defer="slug" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <button type="button" onclick="document.getElementById('slug').value = document.getElementById('title').value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''); Livewire.dispatch('input', { target: document.getElementById('slug') })" class="mt-1 px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm transition">
                                Generuj
                            </button>
                        </div>
                        @error('slug')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Streszczenie -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">Streszczenie</label>
                        <textarea id="excerpt" wire:model.defer="excerpt" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        <p class="mt-1 text-sm text-gray-500">Krótkie streszczenie postu (maksymalnie 500 znaków)</p>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Treść -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Treść</label>
                        <textarea id="content" wire:model.defer="content" rows="10" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategoria -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategoria</label>
                        <select id="category_id" wire:model.defer="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Wybierz kategorię</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" wire:model.defer="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="draft">Szkic</option>
                            <option value="published">Opublikowany</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Obraz -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Obraz</label>
                        <input type="file" id="image" wire:model="image" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <div wire:loading wire:target="image" class="mt-1 text-sm text-blue-500">
                            Ładowanie...
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($currentImage)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $currentImage) }}" alt="{{ $title }}" class="w-32 h-auto object-cover rounded">
                                <p class="text-xs text-gray-500 mt-1">Aktualny obraz</p>
                            </div>
                        @endif
                    </div>

                    <!-- Przyciski nawigacyjne -->
                    <div class="flex justify-between">
                        <a href="{{ route('admin.posts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                            Anuluj
                        </a>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition">
                            Zapisz zmiany
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div> 