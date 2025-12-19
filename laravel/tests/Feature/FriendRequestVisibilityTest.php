<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Email;
use App\Models\User;

class FriendRequestVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_friend_requests_button_not_shown_on_home_and_email_shows_badge()
    {
        $a = User::factory()->create(['role' => 'psikolog']);
        $b = User::factory()->create(['role' => 'psikolog']);

        // A sends friend request (email)
        $this->withSession(['user_id' => $a->id, 'user_role' => $a->role])
            ->post('/friend/' . $b->id);

        // b should see home WITHOUT a "Friend Requests" button but with Email badge
        $this->withSession(['user_id' => $b->id, 'user_role' => $b->role])
            ->get('/home')
            ->assertStatus(200)
            ->assertDontSee('Friend Requests')
            ->assertSee('Email')
            ->assertSee('<span class="email-badge"', false);
    }
}
