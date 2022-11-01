<?php

namespace App\Http\Resources\Api\User;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class LoginResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'status' => true,
            'token' => $this->createToken('login')->plainTextToken,
            'user' => [
                'login' => $this->login,
            ],
        ];
    }
}
