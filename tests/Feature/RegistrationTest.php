<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Регистрация');
        $response->assertSee('Username');
        $response->assertSee('Phonenumber');
    }

    public function test_successful_registration_creates_user_and_link(): void
    {
        $response = $this->post('/register', [
            'username' => 'testuser',
            'phone_number' => '+1234567890',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Ваша уникальная ссылка создана!');

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'phone_number' => '+1234567890',
        ]);

        $user = User::where('username', 'testuser')->first();
        $this->assertNotNull($user);

        $link = Link::where('user_id', $user->id)->first();
        $this->assertNotNull($link);
        $this->assertTrue($link->is_active);
        $this->assertNotNull($link->token);
        $this->assertEquals(32, strlen($link->token));
    }

    public function test_registration_with_existing_username_updates_user(): void
    {
        $existingUser = User::create([
            'username' => 'existinguser',
            'phone_number' => '+1111111111',
        ]);

        $response = $this->post('/register', [
            'username' => 'existinguser',
            'phone_number' => '+2222222222',
        ]);

        $response->assertStatus(200);

        $user = User::where('username', 'existinguser')->first();
        $this->assertEquals('+1111111111', $user->phone_number);
        $this->assertEquals($existingUser->id, $user->id);
    }

    public function test_registration_validation_fails_without_required_fields(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['username', 'phone_number']);
    }
}
