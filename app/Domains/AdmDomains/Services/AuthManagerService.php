<?php

namespace App\Domains\AdmDomains\Services;

use App\Domains\AdmDomains\AuthManagerContract;
use App\Domains\AdmDomains\ManagerException;
use App\Domains\UserDomain\UserDTO\SignInDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthManagerService implements AuthManagerContract
{

    /**
     * @throws ManagerException
     */
    public function authManagerSignIn(SignInDTO $signInDTO) : User
    {
        if (!Auth::attempt([
            'email' => $signInDTO->email,
            'password' => $signInDTO->password
        ])) {
            throw ManagerException::managerNotFound();
        }
        request()->session()->regenerate();

        return User::query()->where('id', Auth::id())->firstOrFail();
    }

    /**
     * @throws ManagerException
     */
    public function authManagerSignOut() : bool
    {
        if (!Auth::check()) {
            throw ManagerException::managerNotFound();
        }

        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return true;
    }

}
