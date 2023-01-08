<?php

namespace App\Http\Requests\Api\Project;

use Illuminate\Foundation\Http\FormRequest;

class ImportProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required',
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
