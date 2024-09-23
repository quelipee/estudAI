<?php

namespace App\Domains\ChatDomain\Services;

use App\Domains\ChatDomain\ChatContracts;
use App\Events\MessageEvent;
use App\Events\MessageUpdatedEvent;
use App\Models\Course;
use App\Models\MessageHistory;
use App\Models\Topic;
use App\Models\User;
use GeminiAPI\ChatSession;
use GeminiAPI\Client;
use GeminiAPI\Enums\Role;
use GeminiAPI\Resources\Content;
use GeminiAPI\Resources\Parts\TextPart;
use GeminiAPI\Responses\GenerateContentResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
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

    public function takeResponseMessage(int $id) : User
    {
        $user = User::find(Auth::id());
        return $user->load(['messageHistory' => function($query) use ($id,$user){
            $query->where('role', Role::Model->name)
                ->where('topic_id',$id)->where('user_id',$user->id);
        }]);
    }

    public function firstInteraction(int $id): array
    {
        $topic = Topic::query()->where('id', $id)->first();
        $topic->load(['messageHistory' => function ($query) {
            $query->where('role', Role::Model->name)->first();
        }]);
        return $topic->toArray();
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

//        Event::dispatch(new MessageEvent($response->text()));
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
        $userMessage = MessageHistory::query()
            ->where('id', $roleModelId)->first();
        Event::dispatch(new MessageUpdatedEvent($userMessage->toArray()));
        return $response;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function add_message_for_chat_histories(Course $course, int $topic, string $type)
    {
        $topic = Topic::query()->where('course_id',$course->id)
            ->where('id',$topic)->first();

        $text = $type . ' sobre o topico ' . $topic->title;

        $message = new MessageHistory([
            'message' => $text,
            'role' => Role::User->name,
        ]);
        $message->user()->associate(Auth::id());
        $message->course()->associate($course);
        $message->topic()->associate($topic);
        $message->save();

        $history = $this->retrieveConversationLog($course, $topic);
         $textForIA = new TextPart($text);
        $response = $this->chat->withHistory($history)->sendMessage($textForIA);

        $model = new MessageHistory([
            'message' => $response->text(),
            'role' => Role::Model->name,
        ]);
        $model->user()->associate(Auth::id());
        $model->course()->associate($course);
        $model->topic()->associate($topic);
        $model->save();

        $res = MessageHistory::query()->where('id',$model->id)->first();
        Event::dispatch(new MessageEvent($res->toArray()));
        return $res;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function message_of_the_day(): string
    {
        $message = 'gera uma mensagem curta de motivação para estudantes';
        $response = $this->client->geminiPro()->generateContent(new TextPart($message));
        return $response->text();
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
