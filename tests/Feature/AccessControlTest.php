<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(Tests\TestCase::class, RefreshDatabase::class);

describe('Authentication', function () {
    test('halaman login dapat ditampilkan', function () {
        $response = $this->get('/login');
        $response->assertStatus(200);
    });

    test('pengguna dapat masuk menggunakan halaman login', function () {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    });

    test('pengguna tidak dapat masuk dengan password salah', function () {
        $user = User::factory()->create();
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
        $this->assertGuest();
    });

    test('pengguna dapat keluar', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/');
    });
});

describe('Dashboard Controller', function () {
    beforeEach(function () {
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $this->ssc = User::factory()->create(['role' => 'ssc']);
        $this->logistik = User::factory()->create(['role' => 'logistik']);
    });

    test('mahasiswa diarahkan ke dashboard mahasiswa', function () {
        $response = $this->actingAs($this->mahasiswa)
            ->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('mahasiswa.dashboard');
    });

    test('ssc diarahkan ke dashboard ssc', function () {
        $response = $this->actingAs($this->ssc)
            ->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('ssc.dashboard');
    });

    test('logistik diarahkan ke dashboard logistik', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('logistik.dashboard');
    });

    test('pengguna tamu diarahkan ke login', function () {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    });
});
