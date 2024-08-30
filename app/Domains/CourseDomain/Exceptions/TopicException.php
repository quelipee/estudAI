<?php

namespace App\Domains\CourseDomain\Exceptions;

class TopicException extends \Exception
{
    public static function topicNotFound($topic): TopicException
    {
        return new self("Topic $topic not found");
    }
}
