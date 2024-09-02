<?php

namespace App\Domains\AdmDomains;

use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Domains\UserDomain\UserRequest\UserSignInRequest;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AdmController extends Controller
{
    public function __construct(
        protected AuthManagerContract  $authManager
    ){}

    public function index(UserSignInRequest $request): Application|Redirector|RedirectResponse
    {
        $this->authManager->authManagerSignIn(
            SignInDTO::fromValidatedRequestSignIn($request)
        );

        return redirect(
            route('index'),
            ResponseAlias::HTTP_CREATED);
    }

    public function signOut(): Application|Redirector|RedirectResponse
    {
        $this->authManager->authManagerSignOut();
        return redirect(
            route('login'),
            ResponseAlias::HTTP_PERMANENTLY_REDIRECT);
    }
}
