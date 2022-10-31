<?php

namespace App\Http\Requests\Api\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectSchemaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'schema' => ['nullable', 'json'],
        ];
    }
}
