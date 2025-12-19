<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_sukses_mengarahkan_ke_home_dan_menyimpan_session()
    {
        $pengguna = User::factory()->create([
            'password' => Hash::make('secret'),
            'role' => 'anonim',
            'is_verified' => true,
        ]);

        $respon = $this->post('/login', ['email' => $pengguna->email, 'password' => 'secret']);
        $respon->assertRedirect('/home');
        $this->assertEquals(session('user_id'), $pengguna->id);
        $this->assertEquals(session('user_role'), $pengguna->role);
    }

    public function test_admin_login_mengarahkan_ke_dashboard_admin()
    {
        $admin = User::factory()->create([
            'password' => Hash::make('adminpass'),
            'is_admin' => true,
            'role' => 'anonim',
            'is_verified' => true,
        ]);

        $respon = $this->post('/login', ['email' => $admin->email, 'password' => 'adminpass']);
        $respon->assertRedirect('/admin/dashboard');
    }

    public function test_login_dengan_password_salah_menampilkan_error()
    {
        $pengguna = User::factory()->create(['password' => Hash::make('rightpass'), 'role' => 'anonim', 'is_verified' => true]);
        $respon = $this->from('/login')->post('/login', ['email' => $pengguna->email, 'password' => 'wrongpass']);
        $respon->assertRedirect('/login');
        $respon->assertSessionHasErrors(['email' => 'Email atau password salah']);
    }

    public function test_login_dengan_email_tidak_ada_menampilkan_error()
    {
        $respon = $this->from('/login')->post('/login', ['email' => 'noone@example.com', 'password' => 'whatever']);
        $respon->assertRedirect('/login');
        $respon->assertSessionHasErrors(['email' => 'Email atau password salah']);
    }

    public function test_validasi_error_ketika_field_kosong()
    {
        $respon = $this->post('/login', []);
        $respon->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_disuspend_tidak_bisa_login()
    {
        $pengguna = User::factory()->create(['password' => Hash::make('pass'), 'is_suspended' => true, 'suspended_reason' => 'spam', 'role' => 'anonim', 'is_verified' => true]);
        $respon = $this->from('/login')->post('/login', ['email' => $pengguna->email, 'password' => 'pass']);
        $respon->assertRedirect('/login');
        $respon->assertSessionHasErrors(['suspended']);
    }

    public function test_psikolog_tidak_terverifikasi_tidak_bisa_login()
    {
        $psikolog = User::factory()->create(['password' => Hash::make('pword'), 'role' => 'psikolog', 'is_verified' => false]);
        $respon = $this->from('/login')->post('/login', ['email' => $psikolog->email, 'password' => 'pword']);
        $respon->assertRedirect('/login');
        $respon->assertSessionHasErrors(['email' => 'Akun Anda belum diverifikasi oleh admin. Silakan tunggu konfirmasi.']);
    }

    public function test_psikolog_terverifikasi_bisa_login()
    {
        $psikolog = User::factory()->create(['password' => Hash::make('pword2'), 'role' => 'psikolog', 'is_verified' => true]);
        $respon = $this->post('/login', ['email' => $psikolog->email, 'password' => 'pword2']);
        $respon->assertRedirect('/home');
    }
}
