<?php

namespace App\Domains\UserDomain\UserException;

class UserException extends \Exception
{
    public static function notLoggedIn(): UserException
    {
        return new self('Not logged in');
    }

    public static function unableToLogout(): UserException
    {
        return new self('Unable to logout');
    }

    public static function invalidCredentials(): UserException
    {
        return new self('Invalid credentials');
    }

    public static function userNotFound(): UserException
    {
        return new self('User not found');
    }

    public static function courseNotFound(): UserException
    {
        return new self('Course not found');
    }

    public static function emailAlreadyExists(): UserException
    {
        return new self('Email already exists');
    }

    public static function userAlreadyEnrolled(): UserException
    {
        return new self('User already enrolled');
    }
}
