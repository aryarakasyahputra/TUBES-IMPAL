<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Message;

class MessageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_image_with_message()
    {
        Storage::fake('public');

        $a = User::factory()->create();
        $b = User::factory()->create();
        // create friendship accepted
        Friendship::create(['user_id' => $a->id, 'friend_id' => $b->id, 'status' => 'accepted']);

        // Use create() with a fake image mime type so tests don't require GD extension
        $file = UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg');

        $this->withSession(['user_id' => $a->id])
            ->post('/messages/' . $b->id, ['attachment' => $file, 'body' => 'Here is a photo'])
            ->assertRedirect('/messages/' . $b->id);

        $msg = Message::first();
        $this->assertNotNull($msg->attachment);
        Storage::disk('public')->assertExists($msg->attachment);
    }

    public function test_body_too_long_is_rejected()
    {
        $a = User::factory()->create();
        $b = User::factory()->create();
        Friendship::create(['user_id' => $a->id, 'friend_id' => $b->id, 'status' => 'accepted']);

        $long = str_repeat('a', 201);

        $this->withSession(['user_id' => $a->id])
            ->post('/messages/' . $b->id, ['body' => $long])
            ->assertSessionHasErrors('body');
    }

    public function test_non_image_attachment_is_rejected()
    {
        Storage::fake('public');

        $a = User::factory()->create();
        $b = User::factory()->create();
        Friendship::create(['user_id' => $a->id, 'friend_id' => $b->id, 'status' => 'accepted']);

        // create a fake non-image file (should fail the image validation)
        $file = UploadedFile::fake()->create('doc.txt', 10, 'text/plain');

        $this->withSession(['user_id' => $a->id])
            ->post('/messages/' . $b->id, ['attachment' => $file, 'body' => 'This should fail'])
            ->assertSessionHasErrors('attachment');
    }
}

