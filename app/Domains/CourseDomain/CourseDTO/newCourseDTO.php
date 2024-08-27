<?php

namespace App\Domains\CourseDomain\CourseDTO;

use App\Domains\CourseDomain\Requests\CourseRequest;
use App\Models\Course;

readonly class newCourseDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $category,
        public array $topics,
    ){}

    public static function fromValidatedNewCourse(CourseRequest $request): newCourseDTO
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            category: $request->validated('category'),
            topics: $request->validated('topics'),
        );
    }
}
