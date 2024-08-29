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
        $user = User::factory()->create(['is_admin' => true]);
        $payload = [
            'title' => 'curso de python basico',
            'description' => 'neste curso de python basico você irá aprender do zero tudo sobre a ferramenta de desenvolvimento.',
            'category' => Category::SoftwareDevelopment->value,
                'topics' => [
                    'Variáveis e Tipos de Dados' => 'Aprenda sobre como armazenar e manipular
                    dados em variáveis, incluindo diferentes tipos de dados como números, strings e listas.',

                    'Estrutura de Controle' => 'Entenda como controlar o fluxo do programa usando instruções como if,
                    else e loops for e while',

                    'Funções' => 'Crie e use funções para modularizar seu código e reutilizar blocos de funcionalidade.',

                    'Listas e Tuplas:' => 'Explore estruturas de dados como listas (arrays dinâmicos) e tuplas (arrays imutáveis)
                    para armazenar e processar coleções de dados.',

                    'Entrada e Saída' => 'Aprenda como obter dados do usuário e exibir resultados usando funções como `input()` e `print()`.'
                ]
        ];

        $response = $this->actingAs($user)->post('api/admin/newCourses', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
    }
    public function test_view_courses()
    {
        $user = User::factory()->create(['is_admin' => true]);
        Course::factory()->create();
        $response = $this->actingAs($user)->get('api/admin/courses');
        $response->assertStatus(Response::HTTP_OK);
    }
    public function test_connectAPI()
    {
        Artisan::call('migrate:refresh --seed');
        $user = User::find(1);
        $course = Course::find(4);
        $user->courses()->attach($course->id);
        $response = $this->actingAs($user)->get('api/app/requestChat/' . $course->id);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_course()
    {
        $user = User::factory()->create(['is_admin' => true]);
        $course = Course::factory()->create()->first();
        Topic::factory()->create();

        $payload = [
            'title' => $course->title,
            'description' => $course->description,
            'category' => $course->category,
            'topics' => $course->topics->toArray(),
        ];

        $response = $this->actingAs($user)->post('api/admin/deleteCourse/' . $course->id, $payload );
        $response->assertStatus(Response::HTTP_OK);
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
            'topics' => [''],
        ];
        $response = $this->actingAs($user)->put('api/admin/updateCourse/' . $course->id, $payload);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('courses', [
            'title' => $payload['title'],
            'description' => $payload['description'],
            'category' => $payload['category']
        ]);
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
