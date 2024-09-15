<?php

namespace App\Domains\ChatDomain\Services;

use App\Domains\ChatDomain\ChatContracts;
use App\Models\Course;
use App\Models\MessageHistory;
use App\Models\Topic;
use GeminiAPI\ChatSession;
use GeminiAPI\Client;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\GenerateContentResponse;
use Illuminate\Support\Facades\Auth;
use Psr\Http\Client\ClientExceptionInterface;

class ChatService implements ChatContracts
{
    private Client $client;
    private ChatSession $chat;
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
        $topic =Topic::query()
            ->where('course_id',$course->id)
            ->where('id',$topic)
            ->first();

        $text = 'me ensine sobre este topico: ' . $topic->title . ' essa é a sua descrição: ' . $topic->description;

        $user = new MessageHistory([
            'message' => $text,
            'role' => Role::User->name,
        ]);
        $user->user()->associate(Auth::id());
        $user->course()->associate($course);
        $user->topic()->associate($topic);
        $user->save();

        $history = $this->retrieveConversationLog($course, $topic);
        $message = new TextPart($text);
        $response = $this->chat->withHistory($history)->sendMessage($message);

        $model = new MessageHistory([
            'message' => $response->text(),
            'role' => Role::Model->name,
        ]);
        $model->user()->associate(Auth::id());
        $model->course()->associate($course);
        $model->topic()->associate($topic);
        $model->save();

        return $response;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function updated_topic_message(Course $course, int $topic, string $roleModelId, string $roleUserId, string $message, string $description): GenerateContentResponse
    {
        $topic =Topic::query()
            ->where('course_id',$course->id)
            ->where('id',$topic)
            ->first();

        $text = 'me ensine sobre este topico: ' . $message . ' essa é a sua descrição: ' . $description;

        MessageHistory::query()->where('id', $roleUserId )->update([
            'message' => $text,
            'role' => Role::User->name,
        ]);

        $history = $this->retrieveConversationLog($course, $topic);
        $message = new TextPart($text);
        $response = $this->chat->withHistory($history)->sendMessage($message);
        MessageHistory::query()->where('id', $roleModelId)->update([
            'message' => $response->text(),
            'role' => Role::Model->name,
        ]);

        return $response;
    }
    public function fetch_message_IA($course, int $messageId): GenerateContentResponse
    {
        // TODO: Implement fetch_message_IA() method.
    }
    public function retrieveConversationLog(Course $course, Topic $topic): array
    {
        $history = [];
        $entries = MessageHistory::query()
            ->where('user_id',Auth::id())
            ->where('course_id', $course->id)
            ->where('topic_id',$topic->id)->get();

        foreach ($entries as $historyEntry)
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
