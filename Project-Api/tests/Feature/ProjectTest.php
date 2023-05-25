<?php

namespace Tests\Feature;

use App\Models\Game;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;
   
    /** @test */
    public function user_can_be_created()
    {
        // $username = $this->faker->username;
        // $email = $this->faker->email;
        // $password = $this->faker->password(8);

        // $user = [
        //     'username' => $username,
        //     'email' => $email,
        //     'password' => $password,
        // ];
        $user = User::factory()->create();

        $response = $this->post('/players');
        $response->assertStatus(200);
        
    }   
    /**@test */

    public function user_registered()
    {
        $user= User::factory()->create();

        $this->post(to_route('register'));



    }

    /**@test */
    public function test_user_can_be_created_without_username(): void
    {
        
        $user = [
            'username' => '',
            'email' => '',
            'password' => 'test',
        ];
        $response = $this->postJson(route('register'), $user);
        $response->assertStatus(422);
         
    }
   
    // /**@test */
    // public function test_user_cant_be_created_without_email()
    // {
    //     $this->artisan('passport:install');
    //     $this->withoutExceptionHandling();
        
    //      $response = $this->postJson('api/players',[
    //         'username'=> 'angel',
    //         'email'=>'',
    //         'password'=>'123456789'
    //      ]);
    //      $response->assertCreated(422);
         
    // }

    // public function test_user_cant_be_created_without_password()
    // {       
    //      $this->artisan('passport:install');
    //      $this->withoutExceptionHandling();
    //         $response = $this->postJson('api/players',[
    //         'username'=> 'angel',
    //         'email'=>'ag@gmail.com',
    //         'password'=>''
    //     ]);
    //     $response->assertCreated(422);
    // }
       
}
