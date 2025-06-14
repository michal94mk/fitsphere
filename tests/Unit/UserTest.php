<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Reservation;
use App\Models\Post;
use App\Models\Comment;
use App\Models\NutritionalProfile;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_correct_attributes()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user'
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('user', $user->role);
    }

    public function test_user_can_check_if_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_can_check_if_trainer()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($trainer->isTrainer());
        $this->assertFalse($user->isTrainer());
    }

    public function test_user_can_get_full_name()
    {
        $user = User::factory()->create([
            'name' => 'John Doe'
        ]);

        $this->assertEquals('John Doe', $user->getFullName());
    }

    public function test_user_can_have_reservations()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->reservations);
        $this->assertEquals($reservation->id, $user->reservations->first()->id);
    }

    public function test_user_can_have_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->posts);
        $this->assertEquals($post->id, $user->posts->first()->id);
    }

    public function test_user_can_have_comments()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->comments);
        $this->assertEquals($comment->id, $user->comments->first()->id);
    }

    public function test_user_can_have_nutritional_profiles()
    {
        $user = User::factory()->create();
        $profile = NutritionalProfile::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->nutritionalProfiles);
        $this->assertEquals($profile->id, $user->nutritionalProfiles->first()->id);
    }

    public function test_user_password_is_hidden_in_array()
    {
        $user = User::factory()->create(['password' => 'secret']);
        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
    }

    public function test_user_email_verified_at_is_date()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->email_verified_at);
    }
} 