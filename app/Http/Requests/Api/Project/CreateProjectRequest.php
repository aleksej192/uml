<?php

namespace App\Http\Requests\Api\Project;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
            'schema' => ['nullable', 'json'],
        ];
    }
}
