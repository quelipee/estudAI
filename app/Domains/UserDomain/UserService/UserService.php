<?php

namespace App\Domains\UserDomain\UserService;

use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Domains\UserDomain\UserDTO\UserDTO;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

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


    /**
     * @throws \Exception
     */
    public function newJoinCourse(Course $course, UserDTO $dto) : User
    {
        $user = User::find($dto->id);
        if (!$user){
            throw new \Exception('User not found');
        }
        if (!$course){
            throw new \Exception('Course not found');
        }
        $user->courses()->attach($course->id);
        return $user->load('courses');
    }

    /**
     * @throws \Exception
     */
    public function serviceSignOut(): bool
    {
        if (!Auth::check()){
            throw new \Exception('Not authenticated');
        }

        $user = Auth::user();
        try {
            $user->tokens()->delete();;
            return true;
        }catch (\Exception $exception){
            Log::error('Error revoking tokens for user ' . $user->id . ': ' . $exception->getMessage());
            throw new Exception('Failed to log out user', $exception);
        }
    }
}
