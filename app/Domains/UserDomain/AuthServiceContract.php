<?php

namespace App\Domains\UserDomain;

use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Models\User;

interface AuthServiceContract
{
    public function serviceSignUp(SignUpDTO $dto) : User;
    public function serviceSignIn(SignInDTO $dto) : array;
    public function serviceSignOut() : bool;
}
