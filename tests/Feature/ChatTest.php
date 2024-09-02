<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function testApiGetResponseForTopicById(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $course = Course::factory()->create([
            'title' => 'curso de html iniciante',
        ])->first();

        $topic = Topic::factory()->create([
            'title' => 'Introdução ao HTML',
        ])->first();
        $user->courses()->attach($course->id);

        $response = $this->actingAs($user)->get('api/app/chat/' . $course->id . '/topic/' . $topic->id . '/message');
        $response->assertStatus(200);
    }
}
