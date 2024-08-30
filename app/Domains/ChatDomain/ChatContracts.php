<?php

namespace App\Domains\ChatDomain;

use App\Models\Course;
use GeminiAPI\Responses\GenerateContentResponse;

interface ChatContracts
{
    public function receive_topic(Course $course, int $topic) : GenerateContentResponse;

    public function retrieveConversationLog() : array;
}
