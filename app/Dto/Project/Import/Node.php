<?php

namespace App\Dto\Project\Import;
use Illuminate\Support\Collection;

class Node
{
    /**
     * @param string $name
     * @param string $type
     * @param Collection|Method[] $methods
     * @param Collection|Property[] $properties
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly Collection $methods,
        public readonly Collection $properties,
    )
    {
    }

    public function toDb(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'methods' => $this->methods->map->toDb(),
            'properties' => $this->properties->map->toDb(),
        ];
    }
}
