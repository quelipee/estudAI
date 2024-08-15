<?php

namespace App\Domains\CourseDomain\API;

use App\Models\Course;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            Content::text('Bom dia, nao responda as minhas perguntas que não forem relacionadas a' . $course->title, Role::User),
            Content::text(
                <<<TEXT
                Bom Dia!!! Vou te ensinar mais sobre `{$course->title}` oque gostaria de aprender?
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
}
