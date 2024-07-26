<?php

namespace App\Domains\CourseDomain\API;

use GeminiAPI\Resources\Parts\TextPart;
use Psr\Http\Client\ClientExceptionInterface;
use GeminiAPI\Resources\Content;
use GeminiAPI\Enums\Role;
class RequestsAPI
{
    public function __construct(
        public \GeminiAPI\Client $cliente,
    ){}

    /**
     * @throws ClientExceptionInterface
     */
    public function requestChat()
    {
// Definindo o histórico
        $history = [
            Content::text('Bom dia', Role::User),
            Content::text(
                <<<TEXT
                <?php
                echo "Hello World!";
                ?>

                This code will print "Bom Dia!" to the standard output.
                TEXT,
                Role::Model,
            ),
        ];

        $chat = $this->cliente->geminiPro()
            ->startChat()
            ->withHistory($history);

        $response = $chat->sendMessage(new TextPart('voce pode me ensinar python?'));
        print_r($response->text());

        return response()->json([
            'message' => 'request to chat ok',
            'request' => $response->text(),
        ]);
    }
}

/*$client = new Client('GEMINI_API_KEY');
$chat = $client->geminiPro()->startChat();

$response = $chat->sendMessage(new TextPart('Hello World in PHP'));
print $response->text();

$response = $chat->sendMessage(new TextPart('in Go'));
print $response->text();*/
