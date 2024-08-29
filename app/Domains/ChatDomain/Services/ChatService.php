<?php

namespace App\Domains\ChatDomain\Services;

use App\Domains\ChatDomain\ChatContracts;
use App\Models\Course;
use App\Models\Topic;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\GenerateContentResponse;
use Psr\Http\Client\ClientExceptionInterface;

class ChatService implements ChatContracts
{
    public function __construct(
        public string $apiKey,
    ){}

    /**
     * @throws ClientExceptionInterface
     */
    public function receive_topic(Course $course, int $topic): GenerateContentResponse
    {
        $topic =Topic::query()->where('course_id',$course->id)->where('id',$topic)->first();
        $client = new Client($this->apiKey);

        $chat = $client->geminiPro()->startChat();
        return $chat->sendMessage(new TextPart('me ensine sobre este topico: ' . $topic->title));
    }
}
