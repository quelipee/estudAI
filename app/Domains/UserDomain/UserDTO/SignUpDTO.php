<?php

namespace App\Domains\UserDomain\UserDTO;
use App\Domains\UserDomain\UserRequest\UserSignUpRequest;
use App\Domains\UserDomain\UserRequest\UserSignInRequest;

readonly class SignUpDTO{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $password_confirmation,
    )
    { }

    public static function fromValidatedRequest(UserSignUpRequest $request): SignUpDTO
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            password_confirmation: $request->validated('password_confirmation'),
        );
    }
}
