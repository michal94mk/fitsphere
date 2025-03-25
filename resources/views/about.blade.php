<x-blog-layout>
    <section class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-center text-gray-900 mb-8">About Us</h1>
            <p class="text-lg text-gray-700 leading-relaxed mb-6">
                Witaj na naszym blogu! Jesteśmy pasjonatami zdrowego stylu życia, fitness i dobrego samopoczucia. Naszym celem jest inspirowanie i pomaganie w osiąganiu celów związanych z fitness i zdrowiem poprzez dostarczanie wartościowych treści i wskazówek.
                Niezależnie od tego, czy jesteś początkującym, czy doświadczonym sportowcem, znajdziesz tu cenne informacje, które poprowadzą Cię na Twojej drodze.
            </p>
            <p class="text-lg text-gray-700 leading-relaxed mb-6">
                Nasz zespół składa się z entuzjastów zdrowia, dietetyków i ekspertów, którzy są oddani dostarczaniu treści najwyższej jakości, aby zmotywować i edukować. Wierzymy, że zdrowy styl życia powinien być przyjemny, angażujący i dostępny dla każdego.
            </p>
            <div class="bg-white shadow-lg rounded-lg p-8 mt-8 text-center">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Nasi Trenerzy</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trainers as $trainer)
                        <div class="text-center border border-gray-300 rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl cursor-pointer">
                            <img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="w-32 h-32 rounded-full mx-auto mb-4">
                            <h3 class="font-medium text-lg text-gray-900">{{ $trainer->name }}</h3>
                            <p class="text-gray-500">{{ $trainer->specialization }}</p>
                            <p class="mt-2 text-sm text-gray-600">{{ Str::limit($trainer->bio, 100) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
                  <!-- Paginacja dla trenerów -->
      <div class="mt-4">
        {{ $trainers->links() }}
      </div>
        </div>
    </section>
</x-blog-layout>

