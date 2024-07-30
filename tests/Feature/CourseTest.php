<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Database\Seeders\CourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_course()
    {
        $payload = [
            'title' => 'curso de python basico',
            'description' => 'neste curso de python basico você irá aprender do zero tudo sobre a ferramenta de desenvolvimento.',
            'category' => 'desenvolvedor',
        ];

        $response = $this->post('api/newCourses', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_connectAPI()
    {
        Artisan::call('migrate:fresh --seed');
        $user = User::find(1);
        $course = Course::find(1);
        $user->courses()->attach($course->id);
        $response = $this->actingAs($user)->get('api/requestChat/' . $course->id);
        $response->assertStatus(Response::HTTP_OK);
    }
}
