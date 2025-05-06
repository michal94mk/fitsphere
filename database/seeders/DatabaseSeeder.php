<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Trainer;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tworzenie administratora
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        // Tworzenie kilku użytkowników
        if (User::count() < 6) { // Admin + 5 użytkowników
            User::factory(5)->create();
        }

        // Tworzenie trenera
        if (!Trainer::where('email', 'trener@example.com')->exists()) {
            Trainer::create([
                'name' => 'Trener Jan',
                'email' => 'trener@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'specialization' => 'Trening siłowy',
                'description' => 'Doświadczony trener z 10-letnim stażem.',
                'bio' => 'Specjalizuję się w treningu siłowym i kondycyjnym. Pomagam osiągnąć cele sportowe w bezpieczny i efektywny sposób.',
                'experience' => 10,
                'is_approved' => true,
            ]);
        }

        // Tworzenie kategorii tylko jeśli nie ma żadnych
        if (Category::count() === 0) {
            $categories = [
                [
                    'name' => 'Trening siłowy',
                ],
                [
                    'name' => 'Cardio',
                ],
                [
                    'name' => 'Dieta',
                ],
                [
                    'name' => 'Zdrowie',
                ],
                [
                    'name' => 'Motywacja',
                ],
            ];

            foreach ($categories as $category) {
                Category::create($category);
            }
        }

        // Tworzenie przykładowych postów tylko jeśli nie ma żadnych
        if (Post::count() === 0) {
            // Tworzenie przykładowych postów
            $posts = [
                [
                    'user_id' => 1,
                    'category_id' => 1,
                    'title' => 'Podstawy treningu siłowego',
                    'slug' => 'podstawy-treningu-silowego',
                    'excerpt' => 'Dowiedz się jak rozpocząć przygodę z treningiem siłowym.',
                    'content' => 'Trening siłowy to forma aktywności fizycznej, która ma na celu wzmocnienie mięśni, poprawę wytrzymałości, zwiększenie masy mięśniowej oraz kształtowanie sylwetki. Polega na wykonywaniu ćwiczeń z oporem, takim jak ciężar własnego ciała, sztangi, hantle, maszyny siłowe czy gumy oporowe. Trening siłowy przynosi wiele korzyści zdrowotnych, w tym zwiększenie gęstości kości, poprawę metabolizmu, redukcję tkanki tłuszczowej i lepszą kontrolę poziomu cukru we krwi.',
                    'status' => 'published',
                ],
                [
                    'user_id' => 1,
                    'category_id' => 2,
                    'title' => 'Jak efektywnie biegać?',
                    'slug' => 'jak-efektywnie-biegac',
                    'excerpt' => 'Poznaj techniki efektywnego biegania dla poprawy kondycji.',
                    'content' => 'Bieganie to jedna z najprostszych i najbardziej dostępnych form aktywności fizycznej. Aby biegać efektywnie, warto zwrócić uwagę na kilka kluczowych aspektów. Po pierwsze, odpowiednia technika - unikaj uderzania piętą o podłoże, stawiaj stopy pod środkiem ciężkości ciała. Po drugie, oddychanie - staraj się oddychać rytmicznie, wdychając powietrze nosem i wydychając ustami. Po trzecie, regularne treningi - aby zauważyć poprawę kondycji, powinieneś biegać co najmniej 3 razy w tygodniu.',
                    'status' => 'published',
                ],
                [
                    'user_id' => 1,
                    'category_id' => 3,
                    'title' => 'Dieta dla sportowca',
                    'slug' => 'dieta-dla-sportowca',
                    'excerpt' => 'Jakie składniki odżywcze są najważniejsze dla aktywnych osób?',
                    'content' => 'Odpowiednio zbilansowana dieta jest kluczowym elementem w osiąganiu sukcesów sportowych. Sportowcy powinni zwrócić szczególną uwagę na podaż białka, które jest niezbędne do regeneracji i budowy mięśni. Zaleca się spożywanie 1,2-2g białka na kilogram masy ciała, w zależności od intensywności treningów. Równie ważne są węglowodany, które stanowią główne źródło energii dla organizmu podczas wysiłku. Nie można też zapominać o zdrowych tłuszczach, które biorą udział w produkcji hormonów i wspierają funkcje układu odpornościowego.',
                    'status' => 'published',
                ],
                [
                    'user_id' => 1,
                    'category_id' => 4,
                    'title' => 'Regeneracja po treningu',
                    'slug' => 'regeneracja-po-treningu',
                    'excerpt' => 'Poznaj najlepsze metody regeneracji po intensywnym wysiłku.',
                    'content' => 'Regeneracja jest równie ważna jak sam trening. Pozwala organizmowi na odbudowę i wzmocnienie mięśni oraz uzupełnienie zapasów energetycznych. Kluczowe elementy regeneracji to: odpowiednia ilość snu (7-9 godzin), właściwe odżywianie (szczególnie w oknie anabolicznym po treningu), nawodnienie oraz techniki relaksacyjne, takie jak rozciąganie, masaż czy sauna. Nie zapominaj też o dniach odpoczynku w planie treningowym - to właśnie wtedy organizm dokonuje największych adaptacji.',
                    'status' => 'published',
                ],
                [
                    'user_id' => 1,
                    'category_id' => 5,
                    'title' => 'Jak nie stracić motywacji?',
                    'slug' => 'jak-nie-stracic-motywacji',
                    'excerpt' => 'Skuteczne sposoby na utrzymanie wysokiej motywacji do treningów.',
                    'content' => 'Utrzymanie motywacji w dłuższej perspektywie czasowej jest wyzwaniem dla wielu osób. Aby nie stracić zapału do treningów, warto stosować kilka sprawdzonych strategii. Ustal konkretne, mierzalne i realistyczne cele. Śledź swoje postępy, prowadząc dziennik treningowy. Wprowadź różnorodność w treningach, aby uniknąć monotonii. Trenuj z partnerem lub w grupie - wsparcie społeczne znacząco zwiększa motywację. Nagradzaj się za osiągnięte cele i pamiętaj, że każdy ma gorsze dni - ważne, aby się nie poddawać i kontynuować treningi nawet przy chwilowym spadku motywacji.',
                    'status' => 'published',
                ],
            ];

            foreach ($posts as $post) {
                Post::create($post);
            }
        }

        // Tworzenie komentarzy tylko jeśli nie ma żadnych
        if (Comment::count() === 0) {
            $comments = [
                [
                    'user_id' => 2,
                    'post_id' => 1,
                    'content' => 'Świetny artykuł, bardzo pomocny dla początkujących!',
                ],
                [
                    'user_id' => 3,
                    'post_id' => 1,
                    'content' => 'Czy możecie polecić jakieś konkretne ćwiczenia na początek?',
                ],
                [
                    'user_id' => 4,
                    'post_id' => 2,
                    'content' => 'Dzięki za wskazówki! Poprawiłem technikę i bieganie stało się dużo przyjemniejsze.',
                ],
                [
                    'user_id' => 5,
                    'post_id' => 3,
                    'content' => 'Ostatnio zmieniłem dietę zgodnie z tymi zaleceniami i zauważyłem znaczącą poprawę w wydolności.',
                ],
                [
                    'user_id' => 2,
                    'post_id' => 4,
                    'content' => 'Regeneracja to coś, co często pomijałem. Teraz widzę, jak ważna jest dla postępów.',
                ],
                [
                    'user_id' => 3,
                    'post_id' => 5,
                    'content' => 'Motywacja to podstawa! Dzięki za te rady, będę je stosować.',
                ],
            ];

            foreach ($comments as $comment) {
                Comment::create($comment);
            }
        }

        // Call all fitness-related seeders
        $this->call([
            TrainerSeeder::class,
            FitnessContentSeeder::class,
        ]);
    }
}
