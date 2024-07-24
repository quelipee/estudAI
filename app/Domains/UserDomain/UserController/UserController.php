<?php
namespace App\Domains\UserDomain\UserController;


use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserDTO\SignUpDTO;
use App\Domains\UserDomain\UserRequest\UserRequest;
use App\Domains\UserDomain\UserRequest\UserSignInRequest;
use App\Domains\UserDomain\UserService\UserService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(public UserService $userService){}

    /**
     * @throws Exception
     */
    public function signUp(UserRequest $request): JsonResponse
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
}
