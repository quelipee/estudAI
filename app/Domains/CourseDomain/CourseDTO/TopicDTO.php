<?php

namespace App\Domains\CourseDomain\CourseDTO;

use App\Domains\CourseDomain\Requests\TopicRequest;
use App\Domains\CourseDomain\Requests\TopicRequestUpdated;

readonly class TopicDTO
{
    public function __construct(
        public string $title,
        public string $topic,
        public int $course_id,
        public ?string $roleUserId,
        public ?string $roleModelId,
    ){}

    public static function fromValidatedNewTopics(TopicRequest $request) : TopicDTO
    {
        return new self(
            title: $request->validated('title'),
            topic: $request->validated('topic'),
            course_id: $request->validated('course_id'),
            roleUserId: $request->validated('roleUserId'),
            roleModelId: $request->validated('roleModelId'),
        );
    }

    public static function fromValidatedUpdatedTopics(TopicRequestUpdated $request) : TopicDTO
    {
        return new self(
            title: $request->validated('title'),
            topic: $request->validated('topic'),
            course_id: $request->validated('course_id'),
            roleUserId: $request->validated('roleUserId'),
            roleModelId: $request->validated('roleModelId')
        );
    }
}
