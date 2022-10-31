<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request): Response
    {
        /** @var User $user */
        $user = User::query()->create([
            'login' => $request->get('login'),
            'password' => Hash::make($request->get('password'))
        ]);

        return response([
            'data' => [
                'status' => true,
                'token' => $user->createToken('login')->plainTextToken,
            ],
        ]);
    }

    public function login(LoginRequest $request): Response
    {
        if (
            $user = User::query()->firstWhere('login', $request->get('login')) and
            Hash::check($request->get('password'), $user->password)
        ) {
            return response([
                'data' => [
                    'status' => true,
                    'token' => $user->createToken('login')->plainTextToken,
                ],
            ]);
        } else {
            return response([
                'data' => [
                    'status' => false,
                    'message' => 'User not found'
                ],
            ], 404);
        }
    }
}
