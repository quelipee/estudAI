<?php
namespace App\Domains\UserDomain\UserController;


use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Domains\UserDomain\UserDTO\UserDTO;
use App\Domains\UserDomain\UserRequest\UserRequest;
use App\Domains\UserDomain\UserRequest\UserSignUpRequest;
use App\Domains\UserDomain\UserRequest\UserSignInRequest;
use App\Domains\UserDomain\UserService\UserService;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(public UserService $userService){}

    /**
     * @throws Exception
     */
    public function signUp(UserSignUpRequest $request): JsonResponse
    {
        $user = $this->userService->
        serviceSignUp(SignUpDTO::fromValidatedRequest($request));

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ],
        201);
    }

    /**
     * @throws Exception
     */
    public function signIn(UserSignInRequest $request): JsonResponse
    {
        $auth = $this->userService->
        serviceSignIn(SignInDTO::fromValidatedRequestSignIn($request));

        return response()->json([
            'message' => 'User login successfully',
            'user' => $auth,
        ],
        200);
    }

    /**
     * @throws Exception
     */
    public function joinCourse(Course $course, UserRequest $request)
    {
        $user_courses = $this->userService->
        newJoinCourse($course, UserDTO::fromValidatedRequest($request));

        return response()->json([
            'message' => 'Course joined successfully',
            'course' => $user_courses,
        ],
        201);
    }
}
