<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_melihat_dashboard()
    {
        $admin = User::factory()->create(['password' => Hash::make('adminpass'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);
        $this->post('/login', ['email' => $admin->email, 'password' => 'adminpass']);

        $respon = $this->get('/admin/dashboard');
        $respon->assertStatus(200);
        $respon->assertSee('Admin Dashboard');
    }

    public function test_admin_memverifikasi_psikolog()
    {
        $admin = User::factory()->create(['password' => Hash::make('a'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);
        $psikolog = User::factory()->create(['role' => 'psikolog', 'is_verified' => false]);

        $this->post('/login', ['email' => $admin->email, 'password' => 'a']);
        $respon = $this->post('/admin/verify/' . $psikolog->id);
        $respon->assertRedirect();
        $this->assertTrue($psikolog->fresh()->is_verified);
    }

    public function test_admin_menolak_psikolog()
    {
        $admin = User::factory()->create(['password' => Hash::make('a'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);
        $psikolog = User::factory()->create(['role' => 'psikolog', 'is_verified' => false]);

        $this->post('/login', ['email' => $admin->email, 'password' => 'a']);
        $respon = $this->post('/admin/reject/' . $psikolog->id);
        $respon->assertRedirect();
        $this->assertNull(User::find($psikolog->id));
    }

    public function test_admin_menangguhkan_dan_membuka_suspensi_user()
    {
        $admin = User::factory()->create(['password' => Hash::make('a'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);
        $pengguna = User::factory()->create();

        $this->post('/login', ['email' => $admin->email, 'password' => 'a']);

        $this->post('/admin/user/' . $pengguna->id . '/suspend', ['action' => 'suspend', 'reason' => 'spam']);
        $this->assertTrue($pengguna->fresh()->is_suspended);
        $this->assertEquals('spam', $pengguna->fresh()->suspended_reason);

        $this->post('/admin/user/' . $pengguna->id . '/suspend', ['action' => 'unsuspend']);
        $this->assertFalse($pengguna->fresh()->is_suspended);
    }

    public function test_admin_mengubah_status_admin_user()
    {
        $admin = User::factory()->create(['password' => Hash::make('a'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);
        $pengguna = User::factory()->create(['is_admin' => false]);

        $this->post('/login', ['email' => $admin->email, 'password' => 'a']);

        $this->post('/admin/user/' . $pengguna->id . '/toggle-admin', []);
        $this->assertTrue($pengguna->fresh()->is_admin);

        // toggle back
        $this->post('/admin/user/' . $pengguna->id . '/toggle-admin', ['allow_self_demote' => '1']);
        $this->assertFalse($pengguna->fresh()->is_admin);
    }

    public function test_admin_mengirim_pesan_kepada_user()
    {
        $admin = User::factory()->create(['password' => Hash::make('a'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);
        $pengguna = User::factory()->create();

        $this->post('/login', ['email' => $admin->email, 'password' => 'a']);

        $this->post('/admin/user/' . $pengguna->id . '/message', ['body' => 'hello']);

        $this->assertDatabaseHas('messages', ['sender_id' => $admin->id, 'recipient_id' => $pengguna->id, 'body' => 'hello']);
    }

    public function test_admin_melihat_sebagai_user_dan_keluar()
    {
        $admin = User::factory()->create(['password' => Hash::make('a'), 'is_admin' => true, 'role' => 'anonim', 'is_verified' => true]);

        $this->post('/login', ['email' => $admin->email, 'password' => 'a']);
        $respon = $this->get('/admin/view-as-user');
        $respon->assertRedirect('/home');
        $this->assertTrue(session('viewing_as_user'));

        $respon2 = $this->get('/admin/exit-view');
        $respon2->assertRedirect('/admin/dashboard');
        $this->assertFalse(session()->has('viewing_as_user'));
    }
}
