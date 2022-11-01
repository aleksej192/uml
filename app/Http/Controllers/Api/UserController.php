<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Http\Resources\Api\User\LoginResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request): JsonResource
    {
        /** @var User $user */
        $user = User::query()->create([
            'login' => $request->get('login'),
            'password' => Hash::make($request->get('password'))
        ]);

        return new LoginResource($user);
    }

    public function login(LoginRequest $request): JsonResponse|JsonResource
    {
        if (
            $user = User::query()->firstWhere('login', $request->get('login')) and
            Hash::check($request->get('password'), $user->password)
        ) {
            return new LoginResource($user);
        } else {
            return JsonResource::make([
                'status' => false,
                'message' => 'User not found'
            ])->response()->setStatusCode(404);
        }
    }
}
