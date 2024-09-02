<?php

namespace App\Domains\AdmDomains;

use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Models\User;

interface AuthManagerContract
{
    public function authManagerSignIn(SignInDTO $signInDTO) : User;

    public function authManagerSignOut() : bool;
}
