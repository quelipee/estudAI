<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CourseTest extends TestCase
{
//    use RefreshDatabase;
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
        $response = $this->get('api/requestChat');
        $response->assertStatus(Response::HTTP_OK);
    }
}
