<?php
namespace App\Domains\UserDomain\UserController;


use App\Domains\UserDomain\AuthServiceContract;
use App\Domains\UserDomain\Resources\UserResource;
use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Domains\UserDomain\UserDTO\UserDTO;
use App\Domains\UserDomain\UserRequest\UserRequest;
use App\Domains\UserDomain\UserRequest\UserSignUpRequest;
use App\Domains\UserDomain\UserRequest\UserSignInRequest;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected AuthServiceContract $userService
    ){}

    /**
     * @throws Exception
     */
    public function signUp(UserSignUpRequest $request)
    {
        $user = $this->userService-> serviceSignUp(SignUpDTO::fromValidatedRequest($request));

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
    public function signOut(): JsonResponse
    {
        $logout = $this->userService->serviceSignOut();
        if($logout === false){
            throw new Exception('Unable to sign out');
        };

        return response()->json([
            'logout' => $logout,
            'message' => 'User logged out successfully',
        ],
        200);
    }

    /**
     * @throws Exception
     */
    public function joinCourse(Course $course): JsonResponse
    {
        $user_courses = $this->userService->
        newJoinCourse($course);

        return response()->json([
            'message' => 'Course joined successfully',
            'course' => $user_courses,
        ],
        201);
    }

    /**
     * @throws Exception
     */
    public function leaveCourse(Course $course, UserRequest $request): JsonResponse
    {
        $leave = $this->userService->deleteUserCourse($course, UserDTO::fromValidatedRequest($request));
        return response()->json([
            'message' => 'Course leaved successfully',
            'status' => $leave
        ],201);
    }

    public function loadUserProfile() : JsonResponse
    {
        $profileResource = $this->userService->getUserProfile();
        return response()->json([
            'message' => 'user profile loaded successfully',
            'profile' => UserResource::make($profileResource)
        ],200);
    }
}
