<?php

namespace App\Domains\CourseDomain\CourseDTO;

use App\Domains\CourseDomain\Requests\TopicRequest;

readonly class TopicDTO
{
    public function __construct(
        public string $title,
        public string $topic,
        public int $course_id,
    ){}

    public static function fromValidatedNewTopics(TopicRequest $request) : TopicDTO
    {
        return new self(
            title: $request->validated('title'),
            topic: $request->validated('topic'),
            course_id: $request->validated('course_id'),
        );
    }
}
