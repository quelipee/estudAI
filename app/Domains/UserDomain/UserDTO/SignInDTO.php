<?php

namespace App\Domains\UserDomain\UserDTO;

use App\Domains\UserDomain\UserRequest\UserRequest;
use App\Domains\UserDomain\UserRequest\UserSignInRequest;

readonly class SignInDTO
{
    public function __construct(
        public string $email,
        public string $password,
    ){}

    public static function fromValidatedRequestSignIn(UserSignInRequest $request): SignInDTO
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password'),
        );
    }
}
