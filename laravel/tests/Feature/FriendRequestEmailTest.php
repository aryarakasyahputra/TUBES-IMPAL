<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Email;
use App\Models\Friendship;

class FriendRequestEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_sending_friend_request_creates_email()
    {
        $a = User::factory()->create(['role' => 'psikolog']);
        $b = User::factory()->create(['role' => 'psikolog']);

        $this->withSession(['user_id' => $a->id, 'user_role' => $a->role])
            ->post('/friend/' . $b->id)
            ->assertRedirect();

        $this->assertDatabaseHas('emails', ['from_user_id' => $a->id, 'to_user_id' => $b->id, 'type' => 'friend_request']);
    }

    public function test_accepting_email_creates_friendship_and_deletes_email()
    {
        $a = User::factory()->create(['role' => 'psikolog']);
        $b = User::factory()->create(['role' => 'psikolog']);

        $email = Email::create(['from_user_id' => $a->id, 'to_user_id' => $b->id, 'subject' => 'Friend request', 'type' => 'friend_request']);

        $this->withSession(['user_id' => $b->id, 'user_role' => $b->role])
            ->post('/friend/' . $email->id . '/accept')
            ->assertRedirect();

        $this->assertDatabaseHas('friendships', ['user_id' => $a->id, 'friend_id' => $b->id, 'status' => 'accepted']);
        $this->assertDatabaseHas('friendships', ['user_id' => $b->id, 'friend_id' => $a->id, 'status' => 'accepted']);
        $this->assertDatabaseMissing('emails', ['id' => $email->id]);
    }

    public function test_rejecting_email_deletes_email()
    {
        $a = User::factory()->create(['role' => 'psikolog']);
        $b = User::factory()->create(['role' => 'psikolog']);

        $email = Email::create(['from_user_id' => $a->id, 'to_user_id' => $b->id, 'subject' => 'Friend request', 'type' => 'friend_request']);

        $this->withSession(['user_id' => $b->id, 'user_role' => $b->role])
            ->post('/friend/' . $email->id . '/reject')
            ->assertRedirect();

        $this->assertDatabaseMissing('emails', ['id' => $email->id]);
    }
}
