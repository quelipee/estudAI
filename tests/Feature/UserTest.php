<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
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

        $response = $this->actingAs($user)->post('api/app/logout');
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

        $response = $this->actingAs($user)->post('api/app/join-course/'.$course->id, $user->toArray());
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_can_leave_the_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $user->courses()->attach($course->id);

        $response = $this->actingAs($user)->post('api/app/leave-course/'.$course->id, $user->toArray());

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_view_courses()
    {
        $user = User::factory()->create();
        Course::factory(5)->create();
        $response = $this->actingAs($user)->get('api/admin/courses');
        $response->assertStatus(Response::HTTP_OK);
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

    public function test_user_no_Register_course_join()
    {
        Artisan::call('migrate:fresh --seed');
        $user = User::find(1);
        $course = Course::find(1);
        $user->courses()->attach($course->id);
        $response = $this->actingAs($user)->post('api/app/join-course/' . $course->id);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_user_get_profile()
    {
        $user = User::factory()->create(['name' => 'felipe', 'email' => 'felipe@felip.com']);
        Course::factory(5)->create();

        $user->courses()->attach([1,2,3]);

        $response = $this->actingAs($user)->get('api/app/profile');
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('users',[
            'name' => 'felipe',
            'email' => 'felipe@felip.com'
        ]);
    }
}
