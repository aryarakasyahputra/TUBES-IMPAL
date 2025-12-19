<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_post()
    {
        // Use 'psikolog' role (non-anonymous) because role enum is limited
        $user = User::factory()->create(['role' => 'psikolog']);

        $this->withSession(['user_id' => $user->id, 'user_role' => $user->role])
            ->post('/posts', ['body' => 'Hello everyone!'])
            ->assertRedirect('/home')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('posts', ['user_id' => $user->id, 'body' => 'Hello everyone!']);
    }

    public function test_user_can_create_post_with_image()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'psikolog']);

        $file = UploadedFile::fake()->create('post.jpg', 150, 'image/jpeg');

        $this->withSession(['user_id' => $user->id, 'user_role' => $user->role])
            ->post('/posts', ['attachment' => $file, 'body' => 'Here is a post image'])
            ->assertRedirect('/home')
            ->assertSessionHas('success');

        $post = Post::first();
        $this->assertNotNull($post->image);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_post_image_is_visible_on_home()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'psikolog']);

        $file = UploadedFile::fake()->create('post2.jpg', 120, 'image/jpeg');
        $path = $file->store('posts', 'public');

        // create a post with that image
        \App\Models\Post::create(['user_id' => $user->id, 'body' => 'img post', 'image' => $path]);

        $this->withSession(['user_id' => $user->id])->get('/home')
            ->assertStatus(200)
            ->assertSee('storage/' . $path);
    }

    public function test_post_non_image_is_rejected()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'psikolog']);
        $file = UploadedFile::fake()->create('doc.txt', 10, 'text/plain');

        $this->withSession(['user_id' => $user->id, 'user_role' => $user->role])
            ->post('/posts', ['attachment' => $file, 'body' => 'nope'])
            ->assertSessionHasErrors('attachment');
    }

    public function test_anon_user_cannot_create_post()
    {
        $user = User::factory()->create(['role' => 'anonim']);

        $this->withSession(['user_id' => $user->id, 'user_role' => $user->role])
            ->post('/posts', ['body' => 'Should not post'])
            ->assertRedirect('/home');

        $this->assertDatabaseMissing('posts', ['user_id' => $user->id, 'body' => 'Should not post']);
    }

    public function test_posting_page_access()
    {
        $user = User::factory()->create(['role' => 'psikolog']);
        $anon = User::factory()->create(['role' => 'anonim']);

        $this->withSession(['user_id' => $user->id, 'user_role' => $user->role])
            ->get('/posting')
            ->assertStatus(200)
            ->assertSee('Bagikan apa yang kamu rasakan');

        // Anon users are redirected away from the posting page
        $this->withSession(['user_id' => $anon->id, 'user_role' => $anon->role])
            ->get('/posting')
            ->assertRedirect('/home');
    }

    public function test_anon_sees_only_psikolog_posts()
    {
        $psik = User::factory()->create(['role' => 'psikolog', 'username' => 'dok']);
        $anonAuthor = User::factory()->create(['role' => 'anonim', 'username' => 'bukan_psik']);

        // psikolog post
        Post::create(['user_id' => $psik->id, 'body' => 'post by psik']);
        // non-psikolog (legacy/other) post
        Post::create(['user_id' => $anonAuthor->id, 'body' => 'post by anon author']);

        $anon = User::factory()->create(['role' => 'anonim']);

        $this->withSession(['user_id' => $anon->id, 'user_role' => $anon->role])
            ->get('/home')
            ->assertSee('post by psik')
            ->assertDontSee('post by anon author');
    }
}
