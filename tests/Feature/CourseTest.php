<?php

namespace Tests\Feature;

use App\Domains\CourseDomain\Enums\Category;
use App\Models\Course;
use App\Models\Topic;
use App\Models\User;
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
        Topic::factory()->create();

        $payload = [
            'title' => 'curso de html iniciante',
            'description' => 'Aprenda os fundamentos da construção de páginas web, criando e estruturando conteúdo com HTML de forma simples e prática, ideal para quem está começando no mundo do desenvolvimento web.',
            'category' => Category::SoftwareDevelopment->value,
            'topics' => [
                'title' => 'Introdução ao HTML',
                'topic' => 'A introdução ao HTML ensina os conceitos básicos da linguagem, como a estrutura de um documento e o uso de tags para criar páginas web.'
            ],
        ];
        $response = $this->actingAs($user)->put('update-course/' . $course->id, $payload);
        $response->assertStatus(ResponseAlias::HTTP_FOUND);
        $this->assertDatabaseHas('courses', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'category' => $payload['category']
        ]);
        $this->assertDatabaseHas('courseTopics',[
           'title' => $payload['topics']['title'],
           'topic' => $payload['topics']['topic'],
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



//    #[NoReturn] public function test123()
//    {
//        $guzzle = new Client();
//
//        $guzzle->request('post','https://univirtus.uninter.com/ava/autenticacao/autenticar/false/Post',[
//            'headers' => [
//                'Content-Type' => 'application/x-www-form-urlencoded',
//                'Accept' => 'application/json, text/javascript, */*; q=0.01',
//            ],
//            'form_params' => [
//                'login' => 13232,
//                'senha' => 493614,
//            ],
//        ]);
//
//        $response = $guzzle->post(
//            'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent',
//            [
//                'headers' => [
//                    'Content-Type' => 'application/json',
//                ],
//                'json' => [
//                    'contents' => [
//                        [
//                            'parts' => [
//                                [
//                                    'text' => 'me fale um pouco sobre o brasil',
//                                ],
//                            ],
//                        ],
//                    ],
//                ],
//                'query' => [
//                    'key' => 'AIzaSyCBZyn8RbkDC3DRcvReylwkysbFMQJ7GdA',
//                ],
//            ]
//        );
//
//        $responseBody = json_decode($response->getBody(), true);
//        dd($responseBody['candidates'][0]['content']['parts'][0]['text']);
//    }
}
