<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
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

    public function test_example_chat(): void
    {
        $course = Course::factory()->create([
            'title' => 'curso de html iniciante',
        ])->first();
        $topic = Topic::factory()->create([
            'title' => 'Introdução ao HTML',
        ])->first();

        $response = $this->post('api/chat/' . 1 . '/topic/' . 1 . '/message');
        $response->assertStatus(200);
    }
}
