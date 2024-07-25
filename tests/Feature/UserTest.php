<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_create_user(): void
    {
        $payload = [
            'name' => 'felipe',
            'email' =>  fake()->email(),
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $response = $this->post('api/register', $payload);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_signIn_user(): void{
        User::create([
            'name' => 'felipe',
            'email' => 'felipe@felip.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $payload = [
            'email' => 'felipe@felip.com',
            'password' => '123456',
        ];

        $response = $this->post('api/login', $payload);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_can_signOut()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('api/logout');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_can_join_course()
    {

        $user = User::factory()->create();
        $course = Course::create([
            'title' => fake()->title,
            'description' => fake()->text(20),
            'category' => 'programação'
        ]);

        $response = $this->actingAs($user)->post('api/join-course/'.$course->id, $user->toArray());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_middleware_in_signIn()
    {
        $user = User::factory()->create(['password' => Hash::make('123456')]);

        $response = $this->actingAs($user)->post('api/login',[
            'email' => 'felipe@felip.com',
            'password' => '123456',
        ]);
        $response->assertStatus(Response::HTTP_FOUND);
    }
}
