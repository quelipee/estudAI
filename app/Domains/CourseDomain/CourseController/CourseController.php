<?php

namespace App\Domains\CourseDomain\CourseController;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\CourseService\CourseService;
use App\Domains\CourseDomain\Requests\CourseRequest;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\NoReturn;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseService
    ){}

    public function courses(): JsonResponse
    {
        $collections = $this->courseService->getAllCourses();
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
        $course = $this->courseService->
        addCourse(newCourseDTO::fromValidatedNewCourse($request));

        return response()->json([
            $course
        ],
        201);
    }
}
