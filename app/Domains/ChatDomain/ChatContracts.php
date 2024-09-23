<?php

namespace App\Domains\ChatDomain;

use App\Models\Course;
use App\Models\Topic;
use GeminiAPI\Responses\GenerateContentResponse;
use App\Models\User;

interface ChatContracts
{
    public function receive_topic(Course $course, int $topic) : GenerateContentResponse;
    public function message_of_the_day() : string;
    public function add_message_for_chat_histories(Course $course, int $topic, string $type);
    public function retrieveConversationLog(Course $course, Topic $topic) : array;
    public function updated_topic_message(Course $course, int $topic, string $roleModelId, string $roleUserId, string $message, string $description);
    public function firstInteraction(int $id) : array;
    public function takeResponseMessage(int $id) : User;
}
