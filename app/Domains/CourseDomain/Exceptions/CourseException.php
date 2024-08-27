<?php

namespace App\Domains\CourseDomain\Exceptions;

class CourseException extends \Exception
{
    public static function courseExists($course) : CourseException
    {
        return new self('Course '.$course.' already exists.');
    }

    public static function courseNotfound($course) : CourseException
    {
        return new self('Course '.$course.' not found.');
    }

    public static function courseNotDeleted($course) : CourseException
    {
        return new self('Course '.$course.' not deleted.');
    }
}
