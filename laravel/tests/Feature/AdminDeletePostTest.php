<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AdminDeletePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_post_and_image_is_removed()
    {
        Storage::fake('public');

        $author = User::factory()->create(['role' => 'psikolog']);
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'anonim']);

        $file = UploadedFile::fake()->create('pic.jpg', 200, 'image/jpeg');
        $path = $file->store('posts', 'public');

        $post = Post::create(['user_id' => $author->id, 'body' => 'Test post', 'image' => $path]);

        $this->withSession(['user_id' => $admin->id, 'is_admin' => true])
            ->post('/admin/post/' . $post->id . '/delete')
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_non_admin_cannot_delete_post()
    {
        $author = User::factory()->create(['role' => 'psikolog']);
        $user = User::factory()->create(['role' => 'psikolog']);

        $post = Post::create(['user_id' => $author->id, 'body' => 'Test post']);

        $this->withSession(['user_id' => $user->id])
            ->post('/admin/post/' . $post->id . '/delete')
            ->assertStatus(403);

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
