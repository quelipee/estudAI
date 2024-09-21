<?php

namespace App\Domains\ChatDomain\Controllers;

use App\Domains\ChatDomain\ChatContracts;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MessageHistory;
use GeminiAPI\Responses\GenerateContentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        protected ChatContracts $chatContracts
    ){}

    public function chatTopic(Course $course, int $topic, Request $request): JsonResponse
    {
        $response =  $this->chatContracts->add_message_for_chat_histories($course,$topic, $request->action);
        return response()->json([
            'message' => $response,
        ],200);
    }

    public function message_day(): JsonResponse
    {
        $response = $this->chatContracts->message_of_the_day();
        return response()->json([
            'message_day' => $response,
        ],200);
    }
}
