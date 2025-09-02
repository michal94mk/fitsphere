<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->assignUserImages();
        $this->assignTrainerImages();
        $this->assignPostImages();
        
        $this->command->info('✅ Obrazki zostały przypisane pomyślnie!');
    }

    /**
     * Przypisuje obrazki do użytkowników
     */
    private function assignUserImages(): void
    {
        $users = User::whereNull('image')->orWhere('image', '')->get();
        $userImages = $this->getImageFiles('users');
        
        $users->each(function ($user, $index) use ($userImages) {
            if (isset($userImages[$index])) {
                $user->update(['image' => 'users/' . $userImages[$index]]);
            }
        });
        
        $this->command->info("🖼️ Przypisano obrazki do {$users->count()} użytkowników");
    }

    /**
     * Przypisuje obrazki do trenerów
     */
    private function assignTrainerImages(): void
    {
        $trainers = User::where('is_trainer', true)
            ->where(function($query) {
                $query->whereNull('image')->orWhere('image', '');
            })->get();
        
        $trainerImages = $this->getImageFiles('trainers');
        
        $trainers->each(function ($trainer, $index) use ($trainerImages) {
            if (isset($trainerImages[$index])) {
                $trainer->update(['image' => 'trainers/' . $trainerImages[$index]]);
            }
        });
        
        $this->command->info("🏋️ Przypisano obrazki do {$trainers->count()} trenerów");
    }

    /**
     * Przypisuje obrazki do postów
     */
    private function assignPostImages(): void
    {
        $posts = Post::whereNull('image')->orWhere('image', '')->get();
        $postImages = $this->getImageFiles('posts');
        
        $posts->each(function ($post, $index) use ($postImages) {
            if (isset($postImages[$index])) {
                $post->update(['image' => 'posts/' . $postImages[$index]]);
            }
        });
        
        $this->command->info("📝 Przypisano obrazki do {$posts->count()} postów");
    }

    /**
     * Pobiera pliki obrazków z danego folderu
     */
    private function getImageFiles(string $folder): array
    {
        $path = storage_path("app/public/{$folder}");
        
        if (!File::exists($path)) {
            return [];
        }
        
        $files = File::files($path);
        $imageFiles = [];
        
        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $imageFiles[] = $file->getFilename();
            }
        }
        
        // Sortuj pliki po nazwie (user1.jpg, user2.jpg, etc.)
        sort($imageFiles, SORT_NATURAL);
        
        return $imageFiles;
    }
}
