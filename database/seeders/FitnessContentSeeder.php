<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Trainer;
use Illuminate\Support\Str;

class FitnessContentSeeder extends Seeder
{
    /**
     * Seed fitness blog content.
     */
    public function run(): void
    {
        // Create fitness categories
        $categories = [
            ['name' => 'Trening siłowy'],
            ['name' => 'Cardio'],
            ['name' => 'Dieta i odżywianie'],
            ['name' => 'Zdrowy styl życia'],
            ['name' => 'Motywacja'],
            ['name' => 'Suplementacja'],
            ['name' => 'Trening funkcjonalny'],
            ['name' => 'Joga i rozciąganie'],
            ['name' => 'Plany treningowe'],
            ['name' => 'Regeneracja'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']]);
        }

        // Get admin user or create one if needed
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        // Create fitness posts
        $posts = [
            [
                'title' => 'Trening siłowy dla początkujących',
                'excerpt' => 'Poznaj podstawy treningu siłowego i rozpocznij swoją przygodę z budowaniem mięśni.',
                'content' => 'Trening siłowy to jedna z najlepszych form aktywności fizycznej. Jako początkujący, powinieneś skupić się na nauce poprawnej techniki wykonywania podstawowych ćwiczeń, takich jak przysiad, martwy ciąg, wyciskanie na ławce i wiosłowanie. Zacznij od 2-3 treningów tygodniowo, dając mięśniom czas na regenerację.',
                'category_id' => Category::where('name', 'Trening siłowy')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Najlepsze ćwiczenia cardio na spalanie tłuszczu',
                'excerpt' => 'Skuteczne ćwiczenia cardio, które pomogą Ci szybciej spalić zbędny tłuszcz.',
                'content' => 'Trening cardio to świetny sposób na spalanie kalorii. Najskuteczniejsze ćwiczenia to: interwały biegowe (HIIT), jazda na rowerze, pływanie, skakanie na skakance i trening na orbitreku. Szczególnie efektywne są treningi interwałowe, polegające na naprzemiennych fazach intensywnego wysiłku i odpoczynku.',
                'category_id' => Category::where('name', 'Cardio')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Dieta ketogeniczna - wszystko co musisz wiedzieć',
                'excerpt' => 'Poznaj zasady diety ketogenicznej i dowiedz się, czy jest odpowiednia dla Ciebie.',
                'content' => 'Dieta ketogeniczna to sposób odżywiania polegający na drastycznym ograniczeniu węglowodanów przy jednoczesnym zwiększeniu podaży tłuszczów. Głównym celem jest wprowadzenie organizmu w stan ketozy, w którym jako główne źródło energii wykorzystywane są ketony zamiast glukozy. Typowa dieta ketogeniczna zawiera około 70-80% kalorii z tłuszczów.',
                'category_id' => Category::where('name', 'Dieta i odżywianie')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Jak zwiększyć masę mięśniową naturalnie',
                'excerpt' => 'Skuteczne metody na budowanie masy mięśniowej bez wspomagania.',
                'content' => 'Budowanie masy mięśniowej w sposób naturalny wymaga cierpliwości i konsekwencji. Kluczowe aspekty to: progresywny trening oporowy, odpowiednie odżywianie (nadwyżka kaloryczna i wystarczająca ilość białka), właściwa regeneracja (7-9 godzin snu), regularne posiłki oraz minimalizacja stresu.',
                'category_id' => Category::where('name', 'Trening siłowy')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => '10 najlepszych źródeł białka w diecie sportowca',
                'excerpt' => 'Poznaj najwartościowsze produkty białkowe dla osób aktywnych fizycznie.',
                'content' => 'Białko jest niezbędnym składnikiem diety każdego sportowca. Oto 10 najlepszych źródeł białka: 1) Pierś z kurczaka, 2) Jaja, 3) Tuńczyk, 4) Łosoś, 5) Wołowina, 6) Twaróg, 7) Soczewica, 8) Tofu, 9) Jogurt grecki, 10) Quinoa. Warto łączyć różne źródła białka w diecie, aby zapewnić kompletny profil aminokwasowy.',
                'category_id' => Category::where('name', 'Dieta i odżywianie')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Jak prawidłowo wykonać martwy ciąg',
                'excerpt' => 'Poznaj technikę wykonania jednego z najlepszych ćwiczeń na całe ciało.',
                'content' => 'Martwy ciąg to jedno z najlepszych ćwiczeń na rozwój siły i masy mięśniowej. Poprawna technika wykonania: 1) Stań przed sztangą, stopy na szerokość bioder. 2) Schyl się i chwyć sztangę. 3) Obniż biodra, wyprostuj plecy. 4) Weź głęboki wdech. 5) Zacznij ruch od wyprostowania nóg. 6) Gdy sztanga minie kolana, pchnij biodra do przodu. 7) Wróć kontrolując ruch.',
                'category_id' => Category::where('name', 'Trening siłowy')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Korzyści z treningu jogi dla sportowców',
                'excerpt' => 'Dowiedz się, jak praktyka jogi może poprawić Twoje wyniki sportowe.',
                'content' => 'Joga to wartościowe uzupełnienie treningu dla sportowców. Główne korzyści to: 1) Poprawa elastyczności i zakresu ruchu, 2) Wzmocnienie mięśni stabilizujących, 3) Lepsza równowaga i koordynacja, 4) Zwiększona świadomość oddechu, 5) Szybsza regeneracja mięśni, 6) Redukcja stresu i poprawa koncentracji.',
                'category_id' => Category::where('name', 'Joga i rozciąganie')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Suplementy - które warto stosować, a które omijać',
                'excerpt' => 'Przewodnik po świecie suplementów dla osób aktywnych fizycznie.',
                'content' => 'W gąszczu dostępnych suplementów łatwo się zagubić. Warto stosować: 1) Kreatynę, 2) Białko serwatkowe, 3) Kofeinę, 4) Beta-alaninę, 5) Elektrolity. Suplementy o wątpliwej skuteczności to: 1) Spalacze tłuszczu, 2) Glutamina, 3) ZMA, 4) HMB. Pamiętaj, że suplementy to tylko dodatek do zbilansowanej diety.',
                'category_id' => Category::where('name', 'Suplementacja')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => '4-tygodniowy plan treningowy dla początkujących',
                'excerpt' => 'Kompletny plan treningowy dla osób rozpoczynających przygodę z siłownią.',
                'content' => 'Oto 4-tygodniowy plan dla początkujących, zakładający 3 treningi w tygodniu. Tydzień 1-2: Trening A: przysiad, wyciskanie, wiosłowanie. Trening B: wykroki, wyciskanie nad głowę, podciąganie. Trening C: martwy ciąg, wyciskanie skośne, przyciąganie do klatki. Tydzień 3-4: Te same ćwiczenia, ale zwiększ ciężar o 5-10%.',
                'category_id' => Category::where('name', 'Plany treningowe')->first()->id,
                'status' => 'published',
            ],
            [
                'title' => 'Dieta śródziemnomorska dla aktywnych osób',
                'excerpt' => 'Poznaj zasady zdrowej diety, która wspiera aktywny tryb życia.',
                'content' => 'Dieta śródziemnomorska świetnie sprawdza się dla osób aktywnych. Jej zasady to: 1) Obfitość warzyw i owoców, 2) Pełnoziarniste produkty zbożowe, 3) Zdrowe tłuszcze z oliwy, orzechów i ryb, 4) Umiarkowane spożycie białka, 5) Ograniczenie czerwonego mięsa, 6) Minimalna ilość przetworzonych produktów.',
                'category_id' => Category::where('name', 'Dieta i odżywianie')->first()->id,
                'status' => 'published',
            ],
        ];

        // Create all posts
        foreach ($posts as $postData) {
            $slug = Str::slug($postData['title']);
            
            // Check if post with this slug already exists
            if (!Post::where('slug', $slug)->exists()) {
                $post = Post::create([
                    'user_id' => $adminUser->id,
                    'title' => $postData['title'],
                    'slug' => $slug,
                    'excerpt' => $postData['excerpt'],
                    'content' => $postData['content'],
                    'category_id' => $postData['category_id'],
                    'status' => $postData['status'],
                    'view_count' => rand(10, 500),
                ]);
            }
        }

        // Get all posts including those that already existed
        $allPosts = Post::all();
        
        // Only create comments if there aren't enough already
        if (Comment::count() < 30) {
            $commentTexts = [
                'Świetny artykuł! Bardzo przydatne informacje, na pewno wykorzystam te porady.',
                'Dzięki za podzielenie się wiedzą. Mam pytanie - jak często powinienem robić takie treningi?',
                'Stosuję się do tych wskazówek od miesiąca i widzę znaczącą poprawę wyników.',
                'To podejście zupełnie zmieniło moje myślenie o treningu. Dziękuję!',
                'Ciekawe informacje, ale czy to podejście sprawdzi się również u osób starszych?',
                'Wypróbowałem tę metodę i jestem pod wrażeniem rezultatów!',
                'Artykuł bardzo merytoryczny, widać że autor ma dużą wiedzę praktyczną.',
                'Polecam wypróbować te wskazówki, u mnie działają znakomicie.',
                'Chyba muszę zrewidować swoje podejście do treningu po przeczytaniu tego tekstu.',
                'Super wyjaśnione, nawet dla laika jak ja. Zaczynam wdrażać od jutra!',
                'Czy możesz rozwinąć temat supelementacji przy tym rodzaju treningu?',
                'Ten plan treningowy jest świetny, stosuję go od 2 tygodni i czuję się znacznie lepiej.',
                'Właśnie tego szukałem, dzięki za uporządkowanie wiedzy na ten temat.',
                'Interesujące podejście, choć trochę inne niż to, co czytałem wcześniej.',
                'Wreszcie konkretne informacje bez zbędnego komplikowania tematu.',
            ];

            $allUsers = User::where('id', '!=', $adminUser->id)->get();

            // Add comments to random posts
            for ($i = 0; $i < 30; $i++) {
                Comment::create([
                    'user_id' => $allUsers->random()->id,
                    'post_id' => $allPosts->random()->id,
                    'content' => $commentTexts[array_rand($commentTexts)],
                ]);
            }
        }
    }
} 