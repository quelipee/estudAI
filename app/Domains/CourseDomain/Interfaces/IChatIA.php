<?php

namespace App\Domains\CourseDomain\Interfaces;

use App\Models\Course;
use Illuminate\Http\JsonResponse;

interface IChatIA
{
    public function requestChat(Course $course) : JsonResponse;
}
