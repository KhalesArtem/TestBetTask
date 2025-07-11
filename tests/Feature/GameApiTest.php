<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Link;
use App\Models\History;
use App\Services\RandomNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameApiTest extends TestCase
{
    use RefreshDatabase;

    private function createActiveLink(): Link
    {
        $user = User::create([
            'username' => 'testuser',
            'phone_number' => '+1234567890',
        ]);

        return Link::create([
            'user_id' => $user->id,
            'token' => 'testtoken12345678901234567890123',
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function test_play_endpoint_creates_history_record(): void
    {
        $link = $this->createActiveLink();

        $response = $this->postJson('/api/game/' . $link->token . '/play');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'random_number',
            'result',
            'win_amount',
        ]);

        $this->assertDatabaseCount('histories', 1);
        
        $history = History::first();
        $this->assertEquals($link->id, $history->link_id);
        $this->assertContains($history->result, ['Win', 'Lose']);
    }

    public function test_win_logic_with_number_950(): void
    {
        $link = $this->createActiveLink();

        // Mock RandomNumberGenerator to return 950
        $this->mock(RandomNumberGenerator::class, function ($mock) {
            $mock->shouldReceive('generate')->once()->andReturn(950);
        });

        $response = $this->postJson('/api/game/' . $link->token . '/play');

        $response->assertStatus(200);
        $response->assertJson([
            'random_number' => 950,
            'result' => 'Win',
            'win_amount' => 665, // 950 * 0.7
        ]);
    }

    public function test_lose_logic_with_number_451(): void
    {
        $link = $this->createActiveLink();

        // Mock RandomNumberGenerator to return 451
        $this->mock(RandomNumberGenerator::class, function ($mock) {
            $mock->shouldReceive('generate')->once()->andReturn(451);
        });

        $response = $this->postJson('/api/game/' . $link->token . '/play');

        $response->assertStatus(200);
        $response->assertJson([
            'random_number' => 451,
            'result' => 'Lose',
            'win_amount' => 0,
        ]);
    }

    public function test_history_endpoint_returns_last_3_records(): void
    {
        $link = $this->createActiveLink();

        // Create 5 history records
        for ($i = 1; $i <= 5; $i++) {
            History::create([
                'link_id' => $link->id,
                'random_number' => $i * 100,
                'result' => $i % 2 == 0 ? 'Win' : 'Lose',
                'win_amount' => $i % 2 == 0 ? $i * 10 : 0,
            ]);
        }

        $response = $this->getJson('/api/game/' . $link->token . '/history');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        
        $data = $response->json();
        
        // Проверим, что возвращаются последние 3 записи
        $this->assertEqualsCanonicalizing(
            [500, 400, 300],
            array_column($data, 'random_number')
        );
    }

    public function test_renew_link_deactivates_old_and_creates_new(): void
    {
        $link = $this->createActiveLink();

        $response = $this->postJson('/api/links/' . $link->token . '/renew');

        $response->assertStatus(200);
        $response->assertJsonStructure(['new_url']);

        $link->refresh();
        $this->assertFalse($link->is_active);

        $newLink = Link::where('user_id', $link->user_id)
            ->where('id', '!=', $link->id)
            ->first();
        
        $this->assertNotNull($newLink);
        $this->assertTrue($newLink->is_active);
        $this->assertEquals(32, strlen($newLink->token));
    }

    public function test_deactivate_link_sets_is_active_to_false(): void
    {
        $link = $this->createActiveLink();

        $response = $this->postJson('/api/links/' . $link->token . '/deactivate');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Ссылка успешно деактивирована']);

        $link->refresh();
        $this->assertFalse($link->is_active);
    }

    public function test_play_with_deactivated_link_returns_403(): void
    {
        $link = $this->createActiveLink();
        $link->update(['is_active' => false]);

        $response = $this->postJson('/api/game/' . $link->token . '/play');

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Ссылка деактивирована']);
    }

    public function test_play_with_expired_link_returns_403(): void
    {
        $link = $this->createActiveLink();
        $link->update(['expires_at' => now()->subDay()]);

        $response = $this->postJson('/api/game/' . $link->token . '/play');

        $response->assertStatus(403);
        $response->assertJson(['error' => 'Срок действия ссылки истек']);
    }
}
