<?php

namespace App\Domains\ChatDomain;

use App\Models\Course;
use App\Models\Topic;
use GeminiAPI\Responses\GenerateContentResponse;

interface ChatContracts
{
    public function receive_topic(Course $course, int $topic) : GenerateContentResponse;

    public function fetch_message_IA($course, int $messageId) : GenerateContentResponse;//TODO ADJUST LATER

    public function add_message_for_chat_histories(Course $course, int $topic, string $type);
    public function retrieveConversationLog(Course $course, Topic $topic) : array;

    public function updated_topic_message(Course $course, int $topic, string $roleModelId, string $roleUserId, string $message, string $description);
}
