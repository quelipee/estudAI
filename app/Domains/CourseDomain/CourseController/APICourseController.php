<?php

namespace App\Domains\CourseDomain\CourseController;

use App\Domains\CourseDomain\Interfaces\APICourseContracts;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class APICourseController extends Controller
{
    public function __construct(
        protected APICourseContracts $courseContracts
    ){}

    public function index(): JsonResponse
    {
        $courses = $this->courseContracts->getUnregisteredCoursesForUser();
        return response()->json([
            'courses' => $courses
        ], ResponseAlias::HTTP_OK);

    }

    public function takeCourse(int $id): JsonResponse
    {
        $course = $this->courseContracts->findCourseById($id);
        return response()->json([
            'course' => $course
        ],ResponseAlias::HTTP_OK);
    }

    public function takeTopicWithCourse(int $id): JsonResponse
    {
        $topic = $this->courseContracts->findTopicById($id);
        return response()->json([
            'course' => $topic
        ],ResponseAlias::HTTP_OK);
    }

    public function pickUpThreads(int $id): JsonResponse
    {
        $topics = $this->courseContracts->getYourTopicsByUser($id);
        return response()->json([
            'topics' => $topics
        ]);
    }

    public function getUserCourseByAuthentication(): JsonResponse
    {
        $courses = $this->courseContracts->getYourCoursesByUser();
        return response()->json([
            'courses' => $courses
        ]);
    }
}
