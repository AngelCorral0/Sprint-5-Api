<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // register testing
    public function test_user_can_register()
    {
        $this->artisan('passport:install');
        $this->withoutExceptionHandling();

        $response = $this->postJson('api/players', [
            'username' => 'username',
            'email' => 'user@email.com',
            'password' => 'password',
        ]);

        $response->assertCreated();
        $user = User::first();
        $this->assertCount(1, User::all());
        $this->assertEquals('username', $user->username);
        $this->assertEquals('user@email.com', $user->email);
        $this->assertDatabaseHas('users', $user->toArray());
    }

    public function test_user_can_register_empty_username()
    {
        $this->artisan('passport:install');

        $response = $this->postJson('api/players', [
            'username' => '',
            'email' => 'user@email.com',
            'password' => 'UserPassword',
            ]);

        $response->assertCreated();
    }
    
    public function test_required_email()
    {
        $this->artisan('passport:install');

        $response = $this->post('api/players', [
            'username' => 'username',
            'email' => '',
            'password' => 'UserPassword',
            
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_required_password()
    {
        $this->artisan('passport:install');

        $response = $this->post('api/players', [
            'username' => 'username',
            'email' => 'user@email.com',
            'password' => '',
            
        ]);

        $response->assertSessionHasErrors(['password']);
    }

   
    public function test_unique_username()
    {
        $this->artisan('passport:install');

        $response = $this->post('api/players', [
            'username' => 'pepe',
            'email' => 'user@email.com',
            'password' => 'password',
            
        ]);

        $response = $this->post('api/players', [
            'username' => 'pepe',
            'email' => 'otheruser@email.com',
            'password' => 'password',
            
        ]);
        //$response->assertJsonValidationErrors(['username']);
        $response->assertSessionHasErrors(['username']);
    }
    
    public function test_unique_email()
    {
        $this->artisan('passport:install');

        $response = $this->post('api/players', [
            'username' => 'username',
            'email' => 'user@email.com',
            'password' => 'password',
            
        ]);

        $response = $this->post('api/players', [
            'username' => 'username',
            'email' => 'user@email.com',
            'password' => 'password',
            
        ]);

        $response->assertSessionHasErrors(['email']);
    }
    
            // LOGIN
    //**@test */
    public function test_user_can_login()
    {
        $this->artisan('passport:install');

        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->post('api/players/login', [
            'email' => $user->email,
            'password' =>$password,
        ]);
        $this->assertJson($user);
        $response->assertStatus(201);
       
    }

    public function test_required_email_at_login()
    {
        $this->artisan('passport:install');

        $response = $this->post('api/players/login', [
            'email' => '',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);
    }

    public function test_required_password_at_login()
    {
        $this->artisan('passport:install');

        $response = $this->post('api/players/login', [
            'email' => 'test@email.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['password']);
        $response->assertStatus(302);
    }

    public function test_errors_validation_login_email()
    {
        $response = $this->post('api/players/login', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
    }

    public function test_errors_validation_login_password()
    {
        $response = $this->post('api/players/login', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
    }

    // // logout testing
    // /**@test */
    // public function test_auth_user_can_logout()
    // {
    //     $this->artisan('passport:install');

    //     $user = User::factory()->create();
    //     Passport::actingAs($user);
    //     $response = $this->postJson('api/players/logout');
    //     $response->assertJson(200);
    // }
}