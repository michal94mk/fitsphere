<div>
    <div class="container mx-auto p-6">
        <!-- Nagłówek z przyciskami i tytułem -->
        <div class="flex items-center justify-between mb-4">
            <!-- Lewa strona: przyciski -->
            <div class="flex space-x-2">
                <a href="{{ route('admin-dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
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

        <!-- Formularz edycji posta -->
        <form wire:submit="update" enctype="multipart/form-data">
            <div class="space-y-4">
                <!-- Tytuł -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Tytuł</label>
                    <input type="text" id="title" wire:model="post.title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('post.title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                    <input type="text" id="slug" wire:model="post.slug" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('post.slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Treść -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Treść</label>
                    <textarea id="content" wire:model="post.content" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                    @error('post.content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategoria -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategoria</label>
                    <select id="category_id" wire:model="post.category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Wybierz kategorię</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('post.category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" wire:model="post.status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="draft">Szkic</option>
                        <option value="published">Opublikowany</option>
                    </select>
                    @error('post.status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Obraz -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Obraz</label>
                    <input type="file" id="image" wire:model="image" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if ($post->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-32">
                        </div>
                    @endif
                </div>

                <!-- Przycisk zapisz -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                        Zapisz zmiany
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 