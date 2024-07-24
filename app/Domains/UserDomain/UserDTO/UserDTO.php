<?php

namespace App\Domains\UserDomain\UserDTO;

use App\Domains\UserDomain\UserRequest\UserRequest;

readonly class UserDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $email_verified_at,
    ){}

    public static function fromValidatedRequest(UserRequest $request): UserDTO{
        return new self(
          id: $request->validated('id'),
          name: $request->validated('name'),
          email: $request->validated('email'),
          email_verified_at: $request->validated('email_verified_at'),
        );
    }
}
