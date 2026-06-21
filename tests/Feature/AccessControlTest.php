<?php

use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
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

describe('Email Verification', function () {
    test('halaman verifikasi email dapat ditampilkan', function () {
        $user = User::factory()->unverified()->create();
        $response = $this->actingAs($user)->get('/verify-email');
        $response->assertStatus(200);
    });

    test('email dapat diverifikasi', function () {
        $user = User::factory()->unverified()->create();
        Event::fake();
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $response = $this->actingAs($user)->get($verificationUrl);
        Event::assertDispatched(Verified::class);
        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
        $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
    });

    test('email tidak diverifikasi dengan hash yang tidak valid', function () {
        $user = User::factory()->unverified()->create();
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );
        $this->actingAs($user)->get($verificationUrl);
        expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
    });
});

describe('Password Confirmation', function () {
    test('halaman konfirmasi password dapat ditampilkan', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/confirm-password');
        $response->assertStatus(200);
    });

    test('password dapat dikonfirmasi', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/confirm-password', ['password' => 'password']);
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    });

    test('password tidak dikonfirmasi dengan password salah', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/confirm-password', ['password' => 'wrong-password']);
        $response->assertSessionHasErrors();
    });
});

describe('Password Reset', function () {
    test('halaman link reset password dapat ditampilkan', function () {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    });

    test('link reset password dapat diminta', function () {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email]);
        Notification::assertSentTo($user, ResetPassword::class);
    });

    test('halaman reset password dapat ditampilkan', function () {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email]);
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);
            $response->assertStatus(200);
            return true;
        });
    });

    test('password dapat direset dengan token valid', function () {
        Notification::fake();
        $user = User::factory()->create();
        $this->post('/forgot-password', ['email' => $user->email]);
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);
            $response->assertSessionHasNoErrors()->assertRedirect(route('login'));
            return true;
        });
    });
});

describe('Password Update', function () {
    test('password dapat diperbarui', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
    });

    test('password yang benar harus diberikan untuk memperbarui password', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);
        $response->assertSessionHasErrorsIn('updatePassword', 'current_password')->assertRedirect('/profile');
    });
});

describe('Registration', function () {
    test('halaman registrasi dapat ditampilkan', function () {
        $response = $this->get('/register');
        $response->assertStatus(200);
    });

    test('pengguna baru dapat mendaftar', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'nim' => '1202201111',
            'prodi' => 'S1 Teknologi Informasi',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    });
});

describe('Calendar Controller', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    test('halaman kalender dapat diakses oleh user yang login', function () {
        $response = $this->actingAs($this->user)
            ->get(route('calendar.index'));

        $response->assertStatus(200);
        $response->assertViewIs('calendar.index');
    });

    test('endpoint events mengembalikan data JSON', function () {
        $ruangan = Ruangan::factory()->create();
        \App\Models\JadwalAkademik::factory()->create([
            'ruangan_id' => $ruangan->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('calendar.events', ['ruangan_id' => $ruangan->id]));

        $response->assertStatus(200);
        $response->assertJsonIsArray();
        
        $events = $response->json();
        expect(count($events))->toBeGreaterThan(0);
        expect($events[0])->toHaveKeys(['title', 'startTime', 'endTime']);
    });

    test('tamu tidak dapat mengakses kalender', function () {
        $response = $this->get(route('calendar.index'));
        $response->assertRedirect(route('login'));
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

describe('Verifikasi Controller', function () {
    test('halaman verifikasi dapat diakses secara publik (tanpa login)', function () {
        $ruangan = Ruangan::factory()->create();
        $user = User::factory()->create();
        
        $reservasi = Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $ruangan->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('verifikasi.show', $reservasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('verifikasi.show');
        $response->assertViewHas('reservasi');
    });

    test('mengembalikan 404 jika reservasi tidak ditemukan', function () {
        $response = $this->get(route('verifikasi.show', 99999));
        $response->assertStatus(404);
    });
});

describe('Profile', function () {
    test('halaman profil ditampilkan', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertOk();
    });

    test('informasi profil dapat diperbarui', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        $user->refresh();
        expect($user->name)->toBe('Test User')
            ->and($user->email)->toBe('test@example.com')
            ->and($user->email_verified_at)->toBeNull();
    });

    test('status verifikasi email tidak berubah jika alamat email tidak berubah', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);
        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        expect($user->refresh()->email_verified_at)->not->toBeNull();
    });

    test('pengguna dapat menghapus akun mereka', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'password',
        ]);
        $response->assertSessionHasNoErrors()->assertRedirect('/');
        $this->assertGuest();
        expect($user->fresh())->toBeNull();
    });

    test('password yang benar harus diberikan untuk menghapus akun', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->from('/profile')->delete('/profile', [
            'password' => 'wrong-password',
        ]);
        $response->assertSessionHasErrors('password')->assertRedirect('/profile');
        expect($user->fresh())->not->toBeNull();
    });
});
