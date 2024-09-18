<?php

namespace App\Domains\UserDomain\UserService;

use App\Domains\UserDomain\AuthServiceContract;
use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Domains\UserDomain\UserDTO\UserDTO;
use App\Domains\UserDomain\UserException\UserException;
use App\Events\YourCourseEvent;
use App\Models\Course;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class UserService implements AuthServiceContract
{
    public function __construct(){}

    /**
     * @throws Exception
     */
    public function serviceSignUp(SignUpDTO $dto): User
    {
        if ($this->CheckExistsEmail($dto->email) === true){
            throw UserException::emailAlreadyExists();
        };

        return User::Create([
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
        return User::where('email', $email)->exists();
    }

    /**
     * @throws Exception
     */
    public function serviceSignIn(SignInDTO $dto): array
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password])) {
            throw UserException::invalidCredentials();
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
     * @throws Exception
     */
    public function newJoinCourse(Course $course)
    {
        $user = Auth::user();
        if (!$user){
            throw UserException::userNotFound();
        }

        if (!$course){
            throw UserException::courseNotFound();
        }
        $exist = $user->courses()->where('course_id', $course->id)->exists();
        if ($exist) {
            throw UserException::userAlreadyEnrolled();
        }

        $user->courses()->attach($course->id);
        Event::dispatch(new YourCourseEvent($user->courses->toArray()));
        return $user->load('courses');
    }

    /**
     * @throws Exception
     */
    public function deleteUserCourse(Course $course, UserDTO $dto) : bool
    {
        $user = User::find($dto->id);
        if (!$user){
            throw UserException::userNotFound();
        }

        if (!$course->id){
            throw UserException::courseNotFound();
        }

        $user->courses()->detach($course);
        return true;
    }

    /**
     * @throws Exception
     */
    public function serviceSignOut(): bool
    {
        if (!Auth::check()){
            throw UserException::notLoggedIn();
        }

        $user = Auth::user();
        try {
            $user->tokens()->delete();;
            return true;
        }catch (UserException $exception){
            Log::error('Error revoking tokens for user ' . $user->id . ': ' . $exception->getMessage());
            throw UserException::unableToLogout();
        }
    }

    /**
     * @throws UserException
     */
    public function getUserProfile() : User
    {
        $user = Auth::user();
        if (!$user){
            throw UserException::notLoggedIn();
        }
        return $user->load('courses');
    }
}
