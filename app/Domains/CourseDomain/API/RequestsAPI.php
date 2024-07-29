<?php

namespace App\Domains\CourseDomain\API;

use App\Models\Course;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\JsonResponse;
use Psr\Http\Client\ClientExceptionInterface;
use GeminiAPI\Resources\Content;
use GeminiAPI\Enums\Role;
class RequestsAPI
{
    public function __construct(
        public \GeminiAPI\Client $cliente,
        public Course $course
    ){}

    /**
     * @throws ClientExceptionInterface
     */
    public function requestChat(Course $course): JsonResponse
    {
    // Definindo o histórico
        $history = [
            Content::text('Bom dia', Role::User),
            Content::text(
                <<<TEXT
                Bom Dia!!! o que gostaria de aprender hoje?
                `{$course->title}`
                TEXT,
                Role::Model,
            ),
        ];

        $chat = $this->cliente->geminiPro()
            ->startChat()
            ->withHistory($history);

        $response = $chat->sendMessage(new TextPart(''));
        print_r($response->text());

        return response()->json([
            'message' => 'request to chat ok',
            'request' => $response->text(),
        ]);
    }

    public function getTitleCourse(): array
    {
        $course = $this->course->all();
        $titles = [];
        foreach ($course as $item){
            $titles[] = $item->title;
        }
        return $titles;
    }
}
