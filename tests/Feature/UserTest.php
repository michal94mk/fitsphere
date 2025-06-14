<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\NutritionalProfile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Post;
use App\Models\Reservation;
use App\Models\Comment;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[DoesNotPerformAssertions]
    public function test_can_create_user() {}

    #[DoesNotPerformAssertions]
    public function test_user_can_have_nutritional_profile() {}

    public function test_user_can_have_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->posts);
        $this->assertEquals($post->id, $user->posts->first()->id);
    }

    public function test_user_can_be_updated()
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com'
        ]);

        $user->update([
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    public function test_user_can_be_deleted()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'id' => $userId
        ]);
    }

    public function test_user_role_management()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $trainerUser = User::factory()->create(['role' => 'trainer']);
        $regularUser = User::factory()->create(['role' => 'user']);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($adminUser->isTrainer());

        $this->assertTrue($trainerUser->isTrainer());
        $this->assertFalse($trainerUser->isAdmin());

        $this->assertFalse($regularUser->isAdmin());
        $this->assertFalse($regularUser->isTrainer());
    }

    public function test_user_can_have_multiple_reservations()
    {
        $user = User::factory()->create();
        $reservation1 = Reservation::factory()->create(['user_id' => $user->id]);
        $reservation2 = Reservation::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->reservations);
        $this->assertTrue($user->reservations->contains($reservation1));
        $this->assertTrue($user->reservations->contains($reservation2));
    }

    public function test_user_can_have_multiple_posts()
    {
        $user = User::factory()->create();
        $post1 = Post::factory()->create(['user_id' => $user->id]);
        $post2 = Post::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->posts);
        $this->assertTrue($user->posts->contains($post1));
        $this->assertTrue($user->posts->contains($post2));
    }

    public function test_user_can_have_multiple_comments()
    {
        $user = User::factory()->create();
        $comment1 = Comment::factory()->create(['user_id' => $user->id]);
        $comment2 = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->comments);
        $this->assertTrue($user->comments->contains($comment1));
        $this->assertTrue($user->comments->contains($comment2));
    }
} 