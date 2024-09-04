<?php

namespace App\Domains\CourseDomain\Enums;

enum Status : string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Pending = 'pending';
}
