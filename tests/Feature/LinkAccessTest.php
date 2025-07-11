<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_access_to_valid_link_is_successful(): void
    {
        $user = User::create([
            'username' => 'testuser',
            'phone_number' => '+1234567890',
        ]);

        $link = Link::create([
            'user_id' => $user->id,
            'token' => 'validtoken123456789012345678901',
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get('/a/' . $link->token);

        $response->assertStatus(200);
        $response->assertSee('Страница A');
        $response->assertSee('Сгенерировать новый линк');
        $response->assertSee('Деактивировать данный линк');
        $response->assertSee("I'm feeling lucky", false);
        $response->assertSee('History');
    }

    public function test_access_to_deactivated_link_returns_403(): void
    {
        $user = User::create([
            'username' => 'testuser',
            'phone_number' => '+1234567890',
        ]);

        $link = Link::create([
            'user_id' => $user->id,
            'token' => 'deactivatedtoken1234567890123456',
            'is_active' => false,
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get('/a/' . $link->token);

        $response->assertStatus(403);
        $response->assertSee('Ссылка деактивирована');
    }

    public function test_access_to_expired_link_returns_403(): void
    {
        $user = User::create([
            'username' => 'testuser',
            'phone_number' => '+1234567890',
        ]);

        $link = Link::create([
            'user_id' => $user->id,
            'token' => 'expiredtoken12345678901234567890',
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->get('/a/' . $link->token);

        $response->assertStatus(403);
        $response->assertSee('Срок действия ссылки истек');
    }

    public function test_access_to_nonexistent_link_returns_404(): void
    {
        $response = $this->get('/a/nonexistenttoken1234567890123456');

        $response->assertStatus(404);
    }
}
