<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{

    public string $token;
    public User $user;
    public string $user_id;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
        if (auth()->user()) {
            $this->user_id = auth()->user()->id;
            $this->user = auth()->user();
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(array $credentials)
    {
        if (!$token = auth()->attempt($credentials)) {
            $this->token = '';
        } else {

            $this->token = $token;
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \App\Models\User
     */
    public function me()
    {
        if (auth()->user()) {
            $this->user = auth()->user();
        } else {
            $this->user = null;
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return bool
     */
    public function logout(): bool
    {
        if (auth()->logout()) {
            return true;
        } else {
            return false;
        };
    }
}
