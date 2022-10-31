<?php

namespace App\Http\Requests\Api\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'login' => ['required', Rule::unique(User::class, 'login'), 'max:255'],
            'password' => ['required', 'max:50'],
        ];
    }
}
