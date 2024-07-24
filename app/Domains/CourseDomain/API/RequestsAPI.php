<?php

namespace App\Domains\CourseDomain\API;

use GeminiAPI\Resources\Parts\TextPart;
use Psr\Http\Client\ClientExceptionInterface;

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
        $response = $this->cliente->geminiPro()->generateContent(
          new TextPart('me de um cronograma de um curso basico de python'),
        );

        return response()->json([
            'message' => 'request to chat ok',
            'request' => $response->text(),
        ]);
    }
}
