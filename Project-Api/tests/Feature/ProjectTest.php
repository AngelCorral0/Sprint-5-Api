<?php

namespace Tests\Feature;

use App\Models\Game;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class ProjectTest extends TestCase
{
    use WithFaker;
   
    public function test_example(): void
    {
        
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    /** @test */
    public function user_can_be_created()
    {
        $this->artisan('passport:install');

        $username = $this->faker->username;
        $email = $this->faker->email;
        $password = $this->faker->password(8);

        $user = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->post(route('register'), $user);
        $response->assertStatus(201);
        
    }   

    /**@test */
    public function test_user_cant_be_created_without_username()
    {
        $this->artisan('passport:install');
        $this->withoutExceptionHandling();
        
         $response = $this->postJson('api/players',[
            'username'=> '',
            'email'=>'ag@gmail.com',
            'password'=>'123456789'
         ]);
         $response->assertCreated(422);
         
    }
    /**@test */
    public function test_user_cant_be_created_without_email()
    {
        $this->artisan('passport:install');
        $this->withoutExceptionHandling();
        
         $response = $this->postJson('api/players',[
            'username'=> 'angel',
            'email'=>'',
            'password'=>'123456789'
         ]);
         $response->assertCreated(422);
         
    }

    public function test_user_cant_be_created_without_password()
    {       
         $this->artisan('passport:install');
         $this->withoutExceptionHandling();
            $response = $this->postJson('api/players',[
            'username'=> 'angel',
            'email'=>'ag@gmail.com',
            'password'=>''
        ]);
        $response->assertCreated(422);
    }
}
