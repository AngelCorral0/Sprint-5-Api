<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;
class ProjectTest extends TestCase
{
   
    public function test_example(): void
    {
        
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    /** @test */
    public function user_can_be_created()
    {
       $this->artisan('passport:install');
        $this->withoutExceptionHandling();
        
         $response = $this->postJson('api/players',[
            'username'=> 'angi',
            'email'=>'angi@gmail.com',
            'password'=>'123456789'
         ]);
         $response->assertCreated();
         $user = User::first();
         $this->assertCount(1, User::all());
         $this->assertEquals('angi', $user->username);
         $this->assertEquals('angi@gmail.com', $user->email);

         $this->assertDatabaseHas('users', $user->toArray());
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
