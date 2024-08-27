<?php

namespace App\Domains\UserDomain;

use App\Domains\UserDomain\Resources\UserResource;
use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Domains\UserDomain\UserDTO\UserDTO;
use App\Models\Course;
use App\Models\User;

interface AuthServiceContract
{
    public function serviceSignUp(SignUpDTO $dto) : User;
    public function serviceSignIn(SignInDTO $dto) : array;
    public function serviceSignOut() : bool;
    public function newJoinCourse(Course $course, UserDTO $dto) : User;
    public function deleteUserCourse(Course $course, UserDTO $dto) : bool;
    public function getUserProfile() : User;
}
