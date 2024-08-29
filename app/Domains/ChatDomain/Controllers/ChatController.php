<?php

namespace App\Domains\ChatDomain\Controllers;

use App\Domains\ChatDomain\ChatContracts;
use App\Http\Controllers\Controller;
use App\Models\Course;
use GeminiAPI\Responses\GenerateContentResponse;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct(
        protected ChatContracts $chatContracts
    ){}

    public function chatTopic(Course $course, int $topic): JsonResponse
    {
        $response =  $this->chatContracts->receive_topic($course,$topic);
        return response()->json([
            'message' => $response,
        ],200);
    }
}
