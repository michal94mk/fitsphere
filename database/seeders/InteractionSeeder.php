<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reservation;

class InteractionSeeder extends Seeder
{
    /**
     * Seed user interactions like comments, views, etc.
     */
    public function run(): void
    {
        $this->seedComments();
        $this->seedReservations();
    }

    private function seedComments(): void
    {
        // Get all posts and users
        $allPosts = Post::all();
        $allUsers = User::all()->filter(function ($user) {
            return !$user->isAdmin();
        });

        if ($allPosts->isEmpty() || $allUsers->isEmpty()) {
            $this->command->error('No posts or users found. Please run other seeders first.');
            return;
        }

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
                'Czy możesz rozwinąć temat suplementacji przy tym rodzaju treningu?',
                'Ten plan treningowy jest świetny, stosuję go od 2 tygodni i czuję się znacznie lepiej.',
                'Właśnie tego szukałem, dzięki za uporządkowanie wiedzy na ten temat.',
                'Interesujące podejście, choć trochę inne niż to, co czytałem wcześniej.',
                'Wreszcie konkretne informacje bez zbędnego komplikowania tematu.',
                'Muszę przyznać, że wcześniej robiłem to zupełnie źle. Dzięki za naukę!',
                'Doskonały materiał! Warto dodać do zakładek i wracać do niego regularnie.',
                'Praktyczne porady, które można od razu wdrożyć. To mi się podoba!',
                'Czy planujecie jakieś webinary na ten temat? Chętnie bym uczestniczył.',
                'Świetnie napisane, przekazuje skomplikowane rzeczy w prosty sposób.',
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
                'I have to admit I was doing this completely wrong before. Thanks for the lesson!',
                'Excellent material! Worth bookmarking and returning to regularly.',
                'Practical advice that can be implemented immediately. I like that!',
                'Are you planning any webinars on this topic? I would love to participate.',
                'Beautifully written, conveys complex things in a simple way.',
            ];

            // Add Polish comments
            for ($i = 0; $i < 15; $i++) {
                Comment::create([
                    'user_id' => $allUsers->random()->id,
                    'post_id' => $allPosts->random()->id,
                    'content' => $commentTexts[array_rand($commentTexts)],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
            
            // Add English comments
            for ($i = 0; $i < 15; $i++) {
                Comment::create([
                    'user_id' => $allUsers->random()->id,
                    'post_id' => $allPosts->random()->id,
                    'content' => $commentTextsEn[array_rand($commentTextsEn)],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            $this->command->info('Created comments for posts');
        } else {
            $this->command->info('Comments already exist, skipping...');
        }
    }
    
    private function seedReservations(): void
    {
        // Get trainers and regular users
        $trainers = User::all()->filter(function ($user) {
            return $user->isTrainer();
        });
        $users = User::all()->filter(function ($user) {
            return $user->isUser() && !$user->isAdmin();
        });

        if ($trainers->isEmpty() || $users->isEmpty()) {
            $this->command->error('No trainers or users found. Please run BaseDataSeeder first.');
            return;
        }

        // Only create reservations if there aren't enough already
        if (Reservation::count() < 20) {
            $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            
            // Create reservations from users to trainers
            for ($i = 0; $i < 10; $i++) {
                $trainer = $trainers->random();
                $user = $users->random();
                $date = now()->addDays(rand(-30, 30))->format('Y-m-d');
                $startHour = rand(8, 18);
                $endHour = $startHour + rand(1, 2);
                
                Reservation::create([
                    'trainer_id' => $trainer->id,
                    'user_id' => $user->id, // Legacy field
                    'client_id' => $user->id,
                    'client_type' => User::class,
                    'date' => $date,
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => 'Seeded reservation for testing purposes',
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
            
            // Create reservations from trainers to other trainers
            for ($i = 0; $i < 5; $i++) {
                $trainer = $trainers->random();
                $clientTrainer = $trainers->where('id', '!=', $trainer->id)->random();
                $date = now()->addDays(rand(-15, 45))->format('Y-m-d');
                $startHour = rand(8, 18);
                $endHour = $startHour + rand(1, 2);
                
                Reservation::create([
                    'trainer_id' => $trainer->id,
                    'user_id' => $clientTrainer->id, // Set for backward compatibility
                    'client_id' => $clientTrainer->id,
                    'client_type' => User::class,
                    'date' => $date,
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => 'Trainer-to-trainer reservation for professional consultation',
                    'created_at' => now()->subDays(rand(1, 15)),
                ]);
            }

            $this->command->info('Created sample reservations');
        } else {
            $this->command->info('Reservations already exist, skipping...');
        }
    }
} 