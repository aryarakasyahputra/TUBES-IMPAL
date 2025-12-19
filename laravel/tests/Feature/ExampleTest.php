<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_gagal_validasi_kosong()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_login_gagal_email_tidak_ditemukan()
    {
        $response = $this->post('/login', [
            'email' => 'tidakada@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_login_gagal_password_salah()
    {
        User::create([
            'name' => 'User Test',
            'username' => 'usertest',
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'user@test.com',
            'password' => 'salahpassword',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_login_psikolog_belum_diverifikasi()
    {
        User::create([
            'name' => 'Psikolog Test',
            'username' => 'psikologtest',
            'email' => 'psikolog@test.com',
            'password' => Hash::make('password123'),
            'role' => 'psikolog',
            'is_verified' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'psikolog@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_login_admin_berhasil()
    {
        User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
            'is_verified' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }
}
