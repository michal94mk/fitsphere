<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Post;
use App\Models\PostTranslation;
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
            [
                'name' => 'Trening siłowy',
                'translations' => [
                    'en' => [
                        'name' => 'Strength Training',
                        'description' => 'Articles about strength training, building muscle and improving power'
                    ]
                ]
            ],
            [
                'name' => 'Cardio',
                'translations' => [
                    'en' => [
                        'name' => 'Cardio',
                        'description' => 'Everything about cardiovascular training, running, and aerobic exercises'
                    ]
                ]
            ],
            [
                'name' => 'Dieta i odżywianie',
                'translations' => [
                    'en' => [
                        'name' => 'Diet and Nutrition',
                        'description' => 'Healthy eating, nutrition plans and dietary advice'
                    ]
                ]
            ],
            [
                'name' => 'Zdrowy styl życia',
                'translations' => [
                    'en' => [
                        'name' => 'Healthy Lifestyle',
                        'description' => 'General wellness, habits and healthy living tips'
                    ]
                ]
            ],
            [
                'name' => 'Motywacja',
                'translations' => [
                    'en' => [
                        'name' => 'Motivation',
                        'description' => 'Stay inspired and motivated on your fitness journey'
                    ]
                ]
            ],
            [
                'name' => 'Suplementacja',
                'translations' => [
                    'en' => [
                        'name' => 'Supplementation',
                        'description' => 'Information about supplements, vitamins and sports nutrition'
                    ]
                ]
            ],
            [
                'name' => 'Trening funkcjonalny',
                'translations' => [
                    'en' => [
                        'name' => 'Functional Training',
                        'description' => 'Training to improve everyday movements and prevent injuries'
                    ]
                ]
            ],
            [
                'name' => 'Joga i rozciąganie',
                'translations' => [
                    'en' => [
                        'name' => 'Yoga and Stretching',
                        'description' => 'Improve flexibility, balance and mental wellbeing'
                    ]
                ]
            ],
            [
                'name' => 'Plany treningowe',
                'translations' => [
                    'en' => [
                        'name' => 'Training Plans',
                        'description' => 'Structured workout plans for different fitness goals'
                    ]
                ]
            ],
            [
                'name' => 'Regeneracja',
                'translations' => [
                    'en' => [
                        'name' => 'Recovery',
                        'description' => 'Rest and recovery techniques to optimize performance'
                    ]
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $translations = $categoryData['translations'] ?? [];
            unset($categoryData['translations']);
            
            $category = Category::firstOrCreate(['name' => $categoryData['name']]);
            
            // Add translations
            foreach ($translations as $locale => $translationData) {
                CategoryTranslation::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'locale' => $locale
                    ],
                    [
                        'name' => $translationData['name'],
                        'slug' => Str::slug($translationData['name']),
                        'description' => $translationData['description'] ?? null
                    ]
                );
            }
        }

        // Get admin user or create one if needed
        $adminUser = User::where('role', 'admin')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin',
                'email' => 'admin@fitsphere.com',
                'email_verified_at' => now(),
                'password' => bcrypt('Password123'),
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
                'translations' => [
                    'en' => [
                        'title' => 'Strength Training for Beginners',
                        'excerpt' => 'Learn the basics of strength training and start your muscle building journey.',
                        'content' => 'Strength training is one of the best forms of physical activity. As a beginner, you should focus on learning proper technique for basic exercises such as squats, deadlifts, bench press, and rows. Start with 2-3 workouts per week, giving your muscles time to recover.'
                    ]
                ]
            ],
            [
                'title' => 'Najlepsze ćwiczenia cardio na spalanie tłuszczu',
                'excerpt' => 'Skuteczne ćwiczenia cardio, które pomogą Ci szybciej spalić zbędny tłuszcz.',
                'content' => 'Trening cardio to świetny sposób na spalanie kalorii. Najskuteczniejsze ćwiczenia to: interwały biegowe (HIIT), jazda na rowerze, pływanie, skakanie na skakance i trening na orbitreku. Szczególnie efektywne są treningi interwałowe, polegające na naprzemiennych fazach intensywnego wysiłku i odpoczynku.',
                'category_id' => Category::where('name', 'Cardio')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'Best Cardio Exercises for Fat Burning',
                        'excerpt' => 'Effective cardio exercises that will help you burn excess fat faster.',
                        'content' => 'Cardio training is a great way to burn calories. The most effective exercises are: running intervals (HIIT), cycling, swimming, jumping rope, and elliptical training. Interval training is particularly effective, consisting of alternating phases of intense effort and rest.'
                    ]
                ]
            ],
            [
                'title' => 'Dieta ketogeniczna - wszystko co musisz wiedzieć',
                'excerpt' => 'Poznaj zasady diety ketogenicznej i dowiedz się, czy jest odpowiednia dla Ciebie.',
                'content' => 'Dieta ketogeniczna to sposób odżywiania polegający na drastycznym ograniczeniu węglowodanów przy jednoczesnym zwiększeniu podaży tłuszczów. Głównym celem jest wprowadzenie organizmu w stan ketozy, w którym jako główne źródło energii wykorzystywane są ketony zamiast glukozy. Typowa dieta ketogeniczna zawiera około 70-80% kalorii z tłuszczów.',
                'category_id' => Category::where('name', 'Dieta i odżywianie')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'Ketogenic Diet - Everything You Need to Know',
                        'excerpt' => 'Learn about the principles of the ketogenic diet and find out if it\'s right for you.',
                        'content' => 'The ketogenic diet is a way of eating that involves drastically reducing carbohydrates while increasing fat intake. The main goal is to put your body into a state of ketosis, where ketones are used as the main energy source instead of glucose. A typical ketogenic diet contains about 70-80% of calories from fat.'
                    ]
                ]
            ],
            [
                'title' => 'Jak zwiększyć masę mięśniową naturalnie',
                'excerpt' => 'Skuteczne metody na budowanie masy mięśniowej bez wspomagania.',
                'content' => 'Budowanie masy mięśniowej w sposób naturalny wymaga cierpliwości i konsekwencji. Kluczowe aspekty to: progresywny trening oporowy, odpowiednie odżywianie (nadwyżka kaloryczna i wystarczająca ilość białka), właściwa regeneracja (7-9 godzin snu), regularne posiłki oraz minimalizacja stresu.',
                'category_id' => Category::where('name', 'Trening siłowy')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'How to Increase Muscle Mass Naturally',
                        'excerpt' => 'Effective methods for building muscle mass without supplements.',
                        'content' => 'Building muscle mass naturally requires patience and consistency. Key aspects include: progressive resistance training, proper nutrition (caloric surplus and adequate protein), proper recovery (7-9 hours of sleep), regular meals, and stress minimization.'
                    ]
                ]
            ],
            [
                'title' => '10 najlepszych źródeł białka w diecie sportowca',
                'excerpt' => 'Poznaj najwartościowsze produkty białkowe dla osób aktywnych fizycznie.',
                'content' => 'Białko jest niezbędnym składnikiem diety każdego sportowca. Oto 10 najlepszych źródeł białka: 1) Pierś z kurczaka, 2) Jaja, 3) Tuńczyk, 4) Łosoś, 5) Wołowina, 6) Twaróg, 7) Soczewica, 8) Tofu, 9) Jogurt grecki, 10) Quinoa. Warto łączyć różne źródła białka w diecie, aby zapewnić kompletny profil aminokwasowy.',
                'category_id' => Category::where('name', 'Dieta i odżywianie')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => '10 Best Protein Sources in an Athlete\'s Diet',
                        'excerpt' => 'Discover the most valuable protein products for physically active people.',
                        'content' => 'Protein is an essential component of every athlete\'s diet. Here are the 10 best sources of protein: 1) Chicken breast, 2) Eggs, 3) Tuna, 4) Salmon, 5) Beef, 6) Cottage cheese, 7) Lentils, 8) Tofu, 9) Greek yogurt, 10) Quinoa. It\'s worth combining different protein sources in your diet to ensure a complete amino acid profile.'
                    ]
                ]
            ],
            [
                'title' => 'Jak prawidłowo wykonać martwy ciąg',
                'excerpt' => 'Poznaj technikę wykonania jednego z najlepszych ćwiczeń na całe ciało.',
                'content' => 'Martwy ciąg to jedno z najlepszych ćwiczeń na rozwój siły i masy mięśniowej. Poprawna technika wykonania: 1) Stań przed sztangą, stopy na szerokość bioder. 2) Schyl się i chwyć sztangę. 3) Obniż biodra, wyprostuj plecy. 4) Weź głęboki wdech. 5) Zacznij ruch od wyprostowania nóg. 6) Gdy sztanga minie kolana, pchnij biodra do przodu. 7) Wróć kontrolując ruch.',
                'category_id' => Category::where('name', 'Trening siłowy')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'How to Properly Perform a Deadlift',
                        'excerpt' => 'Learn the technique of one of the best full-body exercises.',
                        'content' => 'The deadlift is one of the best exercises for developing strength and muscle mass. Proper technique: 1) Stand in front of the barbell, feet hip-width apart. 2) Bend down and grab the barbell. 3) Lower your hips, straighten your back. 4) Take a deep breath. 5) Start the movement by straightening your legs. 6) When the barbell passes your knees, push your hips forward. 7) Return with controlled movement.'
                    ]
                ]
            ],
            [
                'title' => 'Korzyści z treningu jogi dla sportowców',
                'excerpt' => 'Dowiedz się, jak praktyka jogi może poprawić Twoje wyniki sportowe.',
                'content' => 'Joga to wartościowe uzupełnienie treningu dla sportowców. Główne korzyści to: 1) Poprawa elastyczności i zakresu ruchu, 2) Wzmocnienie mięśni stabilizujących, 3) Lepsza równowaga i koordynacja, 4) Zwiększona świadomość oddechu, 5) Szybsza regeneracja mięśni, 6) Redukcja stresu i poprawa koncentracji.',
                'category_id' => Category::where('name', 'Joga i rozciąganie')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'Benefits of Yoga Training for Athletes',
                        'excerpt' => 'Find out how yoga practice can improve your athletic performance.',
                        'content' => 'Yoga is a valuable addition to training for athletes. The main benefits are: 1) Improved flexibility and range of motion, 2) Strengthening of stabilizing muscles, 3) Better balance and coordination, 4) Increased breath awareness, 5) Faster muscle recovery, 6) Stress reduction and improved concentration.'
                    ]
                ]
            ],
            [
                'title' => 'Suplementy - które warto stosować, a które omijać',
                'excerpt' => 'Przewodnik po świecie suplementów dla osób aktywnych fizycznie.',
                'content' => 'W gąszczu dostępnych suplementów łatwo się zagubić. Warto stosować: 1) Kreatynę, 2) Białko serwatkowe, 3) Kofeinę, 4) Beta-alaninę, 5) Elektrolity. Suplementy o wątpliwej skuteczności to: 1) Spalacze tłuszczu, 2) Glutamina, 3) ZMA, 4) HMB. Pamiętaj, że suplementy to tylko dodatek do zbilansowanej diety.',
                'category_id' => Category::where('name', 'Suplementacja')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'Supplements - Which to Use and Which to Avoid',
                        'excerpt' => 'A guide to the world of supplements for physically active people.',
                        'content' => 'It\'s easy to get lost in the jungle of available supplements. Worth using: 1) Creatine, 2) Whey protein, 3) Caffeine, 4) Beta-alanine, 5) Electrolytes. Supplements with questionable effectiveness: 1) Fat burners, 2) Glutamine, 3) ZMA, 4) HMB. Remember that supplements are only an addition to a balanced diet.'
                    ]
                ]
            ],
            [
                'title' => '4-tygodniowy plan treningowy dla początkujących',
                'excerpt' => 'Kompletny plan treningowy dla osób rozpoczynających przygodę z siłownią.',
                'content' => 'Oto 4-tygodniowy plan dla początkujących, zakładający 3 treningi w tygodniu. Tydzień 1-2: Trening A: przysiad, wyciskanie, wiosłowanie. Trening B: wykroki, wyciskanie nad głowę, podciąganie. Trening C: martwy ciąg, wyciskanie skośne, przyciąganie do klatki. Tydzień 3-4: Te same ćwiczenia, ale zwiększ ciężar o 5-10%.',
                'category_id' => Category::where('name', 'Plany treningowe')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => '4-Week Training Plan for Beginners',
                        'excerpt' => 'Complete training plan for people starting their gym adventure.',
                        'content' => 'Here\'s a 4-week plan for beginners, assuming 3 workouts per week. Week 1-2: Workout A: squats, bench press, rows. Workout B: lunges, overhead press, pull-ups. Workout C: deadlifts, incline press, lat pulldowns. Week 3-4: Same exercises, but increase the weight by 5-10%.'
                    ]
                ]
            ],
            [
                'title' => 'Dieta śródziemnomorska dla aktywnych osób',
                'excerpt' => 'Poznaj zasady zdrowej diety, która wspiera aktywny tryb życia.',
                'content' => 'Dieta śródziemnomorska świetnie sprawdza się dla osób aktywnych. Jej zasady to: 1) Obfitość warzyw i owoców, 2) Pełnoziarniste produkty zbożowe, 3) Zdrowe tłuszcze z oliwy, orzechów i ryb, 4) Umiarkowane spożycie białka, 5) Ograniczenie czerwonego mięsa, 6) Minimalna ilość przetworzonych produktów.',
                'category_id' => Category::where('name', 'Dieta i odżywianie')->first()->id,
                'status' => 'published',
                'translations' => [
                    'en' => [
                        'title' => 'Mediterranean Diet for Active People',
                        'excerpt' => 'Learn the principles of a healthy diet that supports an active lifestyle.',
                        'content' => 'The Mediterranean diet works great for active people. Its principles are: 1) Abundance of vegetables and fruits, 2) Whole grain products, 3) Healthy fats from olive oil, nuts, and fish, 4) Moderate protein intake, 5) Limiting red meat, 6) Minimal processed products.'
                    ]
                ]
            ],
        ];

        // Create all posts
        foreach ($posts as $postData) {
            $slug = Str::slug($postData['title']);
            $translations = $postData['translations'] ?? [];
            unset($postData['translations']);
            
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
                
                // Add translations
                foreach ($translations as $locale => $translationData) {
                    PostTranslation::create([
                        'post_id' => $post->id,
                        'locale' => $locale,
                        'title' => $translationData['title'],
                        'slug' => Str::slug($translationData['title']),
                        'excerpt' => $translationData['excerpt'],
                        'content' => $translationData['content']
                    ]);
                }
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

            $commentTextsEn = [
                'Great article! Very useful information, I will definitely use these tips.',
                'Thanks for sharing your knowledge. I have a question - how often should I do such workouts?',
                'I\'ve been following these guidelines for a month and I see significant improvement in my results.',
                'This approach has completely changed my thinking about training. Thank you!',
                'Interesting information, but will this approach also work for older people?',
                'I tried this method and I\'m impressed with the results!',
                'Very informative article, it\'s clear the author has great practical knowledge.',
                'I recommend trying these tips, they work great for me.',
                'I think I need to revise my approach to training after reading this text.',
                'Explained very well, even for a layman like me. I\'m implementing it tomorrow!',
                'Could you elaborate on supplementation for this type of training?',
                'This training plan is great, I\'ve been using it for 2 weeks and I feel much better.',
                'This is exactly what I was looking for, thanks for organizing the knowledge on this topic.',
                'Interesting approach, though a bit different from what I\'ve read before.',
                'Finally concrete information without unnecessarily complicating the topic.',
            ];

            $allUsers = User::where('id', '!=', $adminUser->id)->get();

            // Add comments to random posts
            for ($i = 0; $i < 15; $i++) {
                Comment::create([
                    'user_id' => $allUsers->random()->id,
                    'post_id' => $allPosts->random()->id,
                    'content' => $commentTexts[array_rand($commentTexts)],
                ]);
            }
            
            // Add English comments too
            for ($i = 0; $i < 15; $i++) {
                Comment::create([
                    'user_id' => $allUsers->random()->id,
                    'post_id' => $allPosts->random()->id,
                    'content' => $commentTextsEn[array_rand($commentTextsEn)],
                ]);
            }
        }
    }
} 