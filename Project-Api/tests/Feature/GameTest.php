<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class PlayTest extends TestCase
{
    use RefreshDatabase;
/**@test */
    public function test_auth_player_can_roll_dice()
    {
        $this->artisan('passport:install');
        
        Passport::actingAs(
            $user = User::factory()
            ->create()
        );
        
        $this->post('api/players/'. $user->id . '/games');

        $this->assertDatabaseHas('plays', [
            'user_id' => $user->id,
        ]);
    }

    public function test_unauth_player_cannot_roll_dice()
    {
        $this->artisan('passport:install');
        
        $user = User::factory()
            ->create();
        
        $this->post('api/players/'. $user->id . '/games')
            ->assertRedirect(route('login'));
    }
}

