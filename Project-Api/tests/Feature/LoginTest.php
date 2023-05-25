<?php

namespace Tests\Feature\Http\Controllers\Api;

use Database\Factories\UserFactory;
use Tests\Feature\ProjectTest;
use App\Models\Game;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
class LoginTest extends TestCase
{
   use RefreshDatabase;

   /**
    * A basic feature test example.
    *
    * @return void
    */

    /** @test */
    public function test_login_validation_errors()
    {
        $response = $this->post(route('login'), []);

        $response->assertStatus(302); 
        $response->assertSessionHasErrors(['password', 'email']); 
    }
    public function test_login_authenticates_user_and_login()
    {
        //$this->artisan('passport:install');

        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertStatus(200);
    }
    
}
