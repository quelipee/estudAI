<?php

namespace App\Domains\ChatDomain;

use App\Models\Course;
use App\Models\Topic;
use GeminiAPI\Responses\GenerateContentResponse;

interface ChatContracts
{
    public function receive_topic(Course $course, int $topic) : GenerateContentResponse;

    public function retrieveConversationLog(Course $course, Topic $topic) : array;
}
