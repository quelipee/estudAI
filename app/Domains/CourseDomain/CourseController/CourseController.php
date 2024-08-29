<?php

namespace App\Domains\CourseDomain\CourseController;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\CourseService\CourseService;
use App\Domains\CourseDomain\Interfaces\CourseTopicsContracts;
use App\Domains\CourseDomain\Requests\CourseRequest;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\NoReturn;

class CourseController extends Controller
{
    public function __construct(
        protected CourseTopicsContracts $courseTopicsService,
    ){}

    public function courses(): JsonResponse
    {
        $collections = $this->courseTopicsService->getAllCourses();
        return response()->json([
            'message' => 'OK',
            'courses' => $collections
        ]);
    }

    /**
     * @throws Exception
     */
    public function newCourses(CourseRequest $request): JsonResponse
    {
        $course = $this->courseTopicsService->
        addCourse(newCourseDTO::fromValidatedNewCourse($request));

        return response()->json([
            $course
        ],
        201);
    }

    public function deleteCourse(CourseRequest $request): JsonResponse
    {
        $this->courseTopicsService->destroyCourse(newCourseDTO::fromValidatedNewCourse($request));
        return response()->json([
            'message' => 'Course deleted',
        ],200);
    }

    public function updateCourse(CourseRequest $request, int $id) : JsonResponse
    {
        $courseUpdate = $this->courseTopicsService->updateCourse(newCourseDTO::fromValidatedNewCourse($request), $id);
        return response()->json([
            'message' => 'Course updated',
            'course' => $courseUpdate
        ],200);
    }
}
