<?php

namespace Tests\Feature;

use App\Domains\CourseDomain\Enums\Category;
use App\Models\Course;
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
        $payload = [
            'title' => 'curso de python basico',
            'description' => 'neste curso de python basico você irá aprender do zero tudo sobre a ferramenta de desenvolvimento.',
            'category' => Category::SoftwareDevelopment->value,
        ];

        $response = $this->post('api/newCourses', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
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
