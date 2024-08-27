<?php

namespace App\Domains\CourseDomain\API;

use App\Domains\CourseDomain\Interfaces\IChatIA;
use App\Models\Course;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use Illuminate\Http\JsonResponse;
use Psr\Http\Client\ClientExceptionInterface;

class RequestsAPI implements IChatIA
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
                5 Topicos sobre `{$course->title}`
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
        ],200);
    }
}
