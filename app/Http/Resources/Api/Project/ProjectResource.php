<?php

namespace App\Http\Resources\Api\Project;

use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Project
 */
class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'schema' => $this->schema,
        ];
    }
}
