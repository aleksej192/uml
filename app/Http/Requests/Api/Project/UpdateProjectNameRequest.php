<?php

namespace App\Http\Requests\Api\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectNameRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
        ];
    }
}
