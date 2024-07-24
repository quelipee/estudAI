<?php

namespace App\Domains\UserDomain\UserService;

use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\NoReturn;

class UserService
{
    public function __construct(protected User $user){}


    /**
     * @throws \Exception
     */
    public function serviceSignUp(SignUpDTO $dto): User
    {
        if ($this->CheckExistsEmail($dto->email) === true){
            throw new \Exception('email already exists');
        };

        return New User([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'password_confirmation' => $dto->password_confirmation,
        ]);
    }


    /*
     * check if email exists
     *
     * */
    public function CheckExistsEmail($email) : bool{
        return $this->user->where('email', $email)->exists();
    }

    /**
     * @throws \Exception
     */
    public function serviceSignIn(SignInDTO $dto): array
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw new \Exception('Invalid email or password');
        }

        $user = Auth::user();

        $token  = $user->createToken('auth token')->plainTextToken;

        return  [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token
        ];
    }
}
