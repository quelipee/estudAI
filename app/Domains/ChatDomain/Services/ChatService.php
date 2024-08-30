<?php

namespace App\Domains\ChatDomain\Services;

use App\Domains\ChatDomain\ChatContracts;
use App\Models\Course;
use App\Models\MessageHistory;
use App\Models\Topic;
use GeminiAPI\Client;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\GenerateContentResponse;
use Psr\Http\Client\ClientExceptionInterface;

class ChatService implements ChatContracts
{
    private $client;
    private $chat;
    public function __construct(
        public string $apiKey,
    ){
        $this->client = new Client($this->apiKey);
        $this->chat = $this->client->geminiPro()->startChat();
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function receive_topic(Course $course, int $topic): GenerateContentResponse
    {
        $topic =Topic::query()->where('course_id',$course->id)->where('id',$topic)->first();

        $text = 'me ensine sobre este topico: ' . $topic->title;
        MessageHistory::create([
            'message' => $text,
            'role' => Role::User->name
        ]);

        $history = $this->retrieveConversationLog();

        $message = new TextPart($text);
        $this->history[] = Content::text($message,Role::User);

        $response = $this->chat->withHistory($history)->sendMessage($message);
        MessageHistory::create([
            'message' => $response->text(),
            'role' => Role::Model->name
        ]);

        print_r($response->text());

        return $response;
    }

    public function retrieveConversationLog(): array
    {
        $history = [];
        $entries = MessageHistory::all();
        foreach ($entries->toArray() as $historyEntry)
        {
            $roleMap = [
                Role::User->name => Role::User,
                Role::Model->name => Role::Model,
            ];

            if (array_key_exists($historyEntry['role'], $roleMap)) {
                $history[] = Content::text($historyEntry['message'], $roleMap[$historyEntry['role']]);
            }
        }
        return $history;
    }
}
