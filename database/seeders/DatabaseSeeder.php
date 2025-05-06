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
        // Call all fitness-related seeders first
        $this->call([
            UserSeeder::class,
            TrainerSeeder::class,
            FitnessContentSeeder::class,
        ]);

        // Tworzenie komentarzy tylko jeśli nie ma żadnych
        if (Comment::count() === 0) {
            // Pobierz pierwszy post z bazy danych
            $firstPost = Post::first();
            $secondPost = Post::skip(1)->first();
            $thirdPost = Post::skip(2)->first();
            $fourthPost = Post::skip(3)->first();
            $fifthPost = Post::skip(4)->first();
            
            // Pobierz użytkowników
            $users = User::where('role', 'user')->take(5)->get();
            
            if ($firstPost && count($users) >= 2) {
                $comments = [
                    [
                        'user_id' => $users[0]->id,
                        'post_id' => $firstPost->id,
                        'content' => 'Świetny artykuł, bardzo pomocny dla początkujących!',
                    ],
                    [
                        'user_id' => $users[1]->id,
                        'post_id' => $firstPost->id,
                        'content' => 'Czy możecie polecić jakieś konkretne ćwiczenia na początek?',
                    ],
                ];
                
                if ($secondPost && count($users) >= 3) {
                    $comments[] = [
                        'user_id' => $users[2]->id,
                        'post_id' => $secondPost->id,
                        'content' => 'Dzięki za wskazówki! Poprawiłem technikę i bieganie stało się dużo przyjemniejsze.',
                    ];
                }
                
                if ($thirdPost && count($users) >= 4) {
                    $comments[] = [
                        'user_id' => $users[3]->id,
                        'post_id' => $thirdPost->id,
                        'content' => 'Ostatnio zmieniłem dietę zgodnie z tymi zaleceniami i zauważyłem znaczącą poprawę w wydolności.',
                    ];
                }
                
                if ($fourthPost && count($users) >= 2) {
                    $comments[] = [
                        'user_id' => $users[0]->id,
                        'post_id' => $fourthPost->id,
                        'content' => 'Regeneracja to coś, co często pomijałem. Teraz widzę, jak ważna jest dla postępów.',
                    ];
                }
                
                if ($fifthPost && count($users) >= 2) {
                    $comments[] = [
                        'user_id' => $users[1]->id,
                        'post_id' => $fifthPost->id,
                        'content' => 'Motywacja to podstawa! Dzięki za te rady, będę je stosować.',
                    ];
                }

                foreach ($comments as $comment) {
                    Comment::create($comment);
                }
            }
        }
    }
}
