<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(UserRegisterRequest $req) {
        $role = 1;
        if(isset($req->role)) {
            $role = $req->role;
        }

        $user = User::create([
            'name'      =>  $req->name,
            'email'     =>  $req->email,
            'password'  =>  bcrypt($req->password),
            'role'      =>  $role,
            'planet'    =>  $req->planet,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'data' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(UserLoginRequest $req) {
        $credentials = $req->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'data' => auth()->user(),
            'token' => $token,
        ], 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $req) {
        return response()->json([
            'data' => $req->user()
        ]);
    }
}
