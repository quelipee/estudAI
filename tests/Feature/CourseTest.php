<?php

namespace Tests\Feature;

use App\Domains\CourseDomain\Enums\Category;
use App\Domains\CourseDomain\Enums\Status;
use App\Models\Course;
use App\Models\MessageHistory;
use App\Models\Topic;
use App\Models\User;
use GeminiAPI\Enums\Role;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */

    public function test_create_course()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $payload = [
            'title' => 'curso de python basico',
            'description' => 'neste curso de python basico você irá aprender do zero tudo sobre a ferramenta de desenvolvimento.',
            'category' => Category::SoftwareDevelopment->value,
            'topics' => [
                [
                    'title' => 'Variáveis e Tipos de Dados',
                    'topic' => 'Aprenda sobre como armazenar e manipular dados em variáveis, incluindo diferentes tipos de dados como números, strings e listas.'
                ],
                [
                    'title' => 'Estrutura de Controle',
                    'topic' => 'Entenda como controlar o fluxo do programa usando instruções como if,else e loops for e while'
                ],
                [
                    'title' => 'Funções',
                    'topic' => 'Crie e use funções para modularizar seu código e reutilizar blocos de funcionalidade.'
                ],
            ]
        ];

        $response = $this->actingAs($user)->post('new-course', $payload);
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_view_courses()
    {
        $user = User::factory()->create(['is_admin' => true]);
        Course::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_course()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create()->first();
        Topic::factory()->create();

        $response = $this->actingAs($user)->delete('delete-course/' . $course->id);
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_update_course()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create(['id' => 11])->first();

        $payload = [
            'title' => 'curso de html iniciante',
            'description' => 'Aprenda os fundamentos da construção de páginas web, criando e estruturando conteúdo com HTML de forma simples e prática, ideal para quem está começando no mundo do desenvolvimento web.',
            'category' => Category::SoftwareDevelopment->value,
            'status' => Status::Completed->value,
        ];
        $response = $this->actingAs($user)->put('update-course/' . $course->id, $payload);
        $response->assertStatus(ResponseAlias::HTTP_FOUND);
        $this->assertDatabaseHas('courses', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'category' => $payload['category']
        ]);
    }

    public function test_insert_topics_in_courses()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create()->first();
        $user->courses()->attach($course->id);
        $payload = [
            'title' => 'aprenda tags html',
            'topic' => 'aprenda tags html em uma semana',
            'course_id' => $course->id,
        ];

        $response = $this->actingAs($user)->post('add-topic', $payload);
        $response->assertStatus(ResponseAlias::HTTP_FOUND);
    }

    public function test_delete_topic()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create()->first();
        $user->courses()->attach($course->id);
        Topic::factory()->create();
        $topic = Topic::query()->where('course_id', $course->id)->first();

        $response = $this->actingAs($user)->delete('delete-topic/' . $topic->id);
        $response->assertStatus(ResponseAlias::HTTP_FOUND);
    }

    public function test_update_topic()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create()->first();
        $user->courses()->attach($course->id);
        $topic = Topic::factory()->create(['title' => 'python'])->first();
        $messageUser = MessageHistory::factory()->create([
            'message' => 'league of legends',
            'role' => Role::User->name
        ])->first();
        $messageModel = MessageHistory::factory()->create([
            'id' => fake()->uuid(),
            'message' => fake()->realText(),
            'role' => Role::Model->name
        ])->first();

        var_dump($messageUser->message);
        var_dump($messageModel->message);
        $payload = [
            'title' => 'tags html',
            'topic' => 'tags html em uma semana',
            'course_id' => $course->id,
            'roleUserId' => $messageUser->id,
            'roleModelId' => $messageModel->id,
            'message' => 'html'
        ];

        $response = $this->actingAs($user)->put('update-topic/' . $topic->id, $payload);
        $response->assertStatus(ResponseAlias::HTTP_FOUND);
        $this->assertDatabaseHas('courseTopics', [
            'title' => $payload['title'],
            'topic' => $payload['topic'],
        ]);
    }
}
