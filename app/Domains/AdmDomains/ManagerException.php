<?php

namespace App\Domains\AdmDomains;

class ManagerException extends \Exception
{
    public static function userNotAuthenticated(): ManagerException
    {
        return new self("User not authenticated");
    }

    public static function managerNotFound(): ManagerException
    {
        return new self("Manager not found");
    }
}
