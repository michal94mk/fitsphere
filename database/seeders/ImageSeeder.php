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
        $force = $this->command->option('force') ?? false;
        
        if ($force) {
            $this->command->info('🚀 Tryb FORCE - przypisuję obrazki do wszystkich rekordów!');
        } else {
            $this->command->info('🔍 Tryb normalny - przypisuję obrazki tylko do rekordów bez obrazków');
        }
        
        $this->assignUserImages($force);
        $this->assignTrainerImages($force);
        $this->assignPostImages($force);
        
        $this->command->info('✅ Obrazki zostały przypisane pomyślnie!');
    }

    /**
     * Przypisuje obrazki do użytkowników
     */
    private function assignUserImages(bool $force = false): void
    {
        // Sprawdź czy użytkownicy mają już obrazki
        $usersWithImages = User::whereNotNull('image')->where('image', '!=', '')->count();
        $usersWithoutImages = User::whereNull('image')->orWhere('image', '')->count();
        
        $this->command->info("🔍 Użytkownicy z obrazkami: {$usersWithImages}");
        $this->command->info("🔍 Użytkownicy bez obrazków: {$usersWithoutImages}");
        
        // Pobierz wszystkich użytkowników (z force) lub tylko bez obrazków
        $users = $force 
            ? User::all() 
            : User::whereNull('image')->orWhere('image', '')->get();
        $userImages = $this->getImageFiles('users');
        
        $this->command->info("🔍 Znaleziono {$users->count()} użytkowników");
        $this->command->info("🔍 Znaleziono " . count($userImages) . " obrazków użytkowników");
        
        $users->each(function ($user, $index) use ($userImages) {
            if (isset($userImages[$index])) {
                $user->update(['image' => 'users/' . $userImages[$index]]);
                $this->command->info("✅ Przypisano {$userImages[$index]} do użytkownika {$user->name}");
            } else {
                $this->command->info("❌ Brak obrazka dla użytkownika {$user->name} (index: {$index})");
            }
        });
        
        $this->command->info("🖼️ Przypisano obrazki do {$users->count()} użytkowników");
    }

    /**
     * Przypisuje obrazki do trenerów
     */
    private function assignTrainerImages(bool $force = false): void
    {
        // Sprawdź ile trenerów ma obrazki
        $trainersWithImages = User::where('role', 'trainer')
            ->whereNotNull('image')->where('image', '!=', '')->count();
        $trainersWithoutImages = User::where('role', 'trainer')
            ->where(function($query) {
                $query->whereNull('image')->orWhere('image', '');
            })->count();
        
        $this->command->info("🔍 Trenerzy z obrazkami: {$trainersWithImages}");
        $this->command->info("🔍 Trenerzy bez obrazków: {$trainersWithoutImages}");
        
        // Pobierz wszystkich trenerów (z force) lub tylko bez obrazków
        $trainers = $force 
            ? User::where('role', 'trainer')->get()
            : User::where('role', 'trainer')
                ->where(function($query) {
                    $query->whereNull('image')->orWhere('image', '');
                })->get();
        
        $trainerImages = $this->getImageFiles('trainers');
        
        $this->command->info("🔍 Znaleziono {$trainers->count()} trenerów");
        $this->command->info("🔍 Znaleziono " . count($trainerImages) . " obrazków trenerów");
        
        $trainers->each(function ($trainer, $index) use ($trainerImages) {
            if (isset($trainerImages[$index])) {
                $trainer->update(['image' => 'trainers/' . $trainerImages[$index]]);
                $this->command->info("✅ Przypisano {$trainerImages[$index]} do trenera {$trainer->name}");
            } else {
                $this->command->info("❌ Brak obrazka dla trenera {$trainer->name} (index: {$index})");
            }
        });
        
        $this->command->info("🏋️ Przypisano obrazki do {$trainers->count()} trenerów");
    }

    /**
     * Przypisuje obrazki do postów
     */
    private function assignPostImages(bool $force = false): void
    {
        // Sprawdź ile postów ma obrazki
        $postsWithImages = Post::whereNotNull('image')->where('image', '!=', '')->count();
        $postsWithoutImages = Post::whereNull('image')->orWhere('image', '')->count();
        
        $this->command->info("🔍 Posty z obrazkami: {$postsWithImages}");
        $this->command->info("🔍 Posty bez obrazków: {$postsWithoutImages}");
        
        // Pobierz wszystkie posty (z force) lub tylko bez obrazków
        $posts = $force 
            ? Post::all()
            : Post::whereNull('image')->orWhere('image', '')->get();
        $postImages = $this->getImageFiles('posts');
        
        $this->command->info("🔍 Znaleziono {$posts->count()} postów");
        $this->command->info("🔍 Znaleziono " . count($postImages) . " obrazków postów");
        
        $posts->each(function ($post, $index) use ($postImages) {
            if (isset($postImages[$index])) {
                $post->update(['image' => 'posts/' . $postImages[$index]]);
                $this->command->info("✅ Przypisano {$postImages[$index]} do posta '{$post->title}'");
            } else {
                $this->command->info("❌ Brak obrazka dla posta '{$post->title}' (index: {$index})");
            }
        });
        
        $this->command->info("📝 Przypisano obrazki do {$posts->count()} postów");
    }

    /**
     * Pobiera pliki obrazków z danego folderu
     */
    private function getImageFiles(string $folder): array
    {
        $path = storage_path("app/public/images/{$folder}");
        
        if (!File::exists($path)) {
            $this->command->info("⚠️ Folder {$path} nie istnieje!");
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
        
        $this->command->info("📁 Skanuję folder: {$path}");
        $this->command->info("📁 Znalezione pliki: " . implode(', ', $imageFiles));
        
        return $imageFiles;
    }
}
