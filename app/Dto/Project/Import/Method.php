<?php

namespace App\Dto\Project\Import;

use Illuminate\Support\Collection;

class Method
{
    /**
     * @param string $visibility
     * @param string $name
     * @param string|null $type
     * @param Collection|Parameter[] $parameters
     */
    public function __construct(
        public readonly string $visibility,
        public readonly string $name,
        public readonly ?string $type,
        public readonly Collection $parameters,
    )
    {
    }

    public function toDb(): array
    {
        return [
            'name' => $this->name,
            'visibility' => $this->visibility,
            'parameters' => $this->parameters->map->toDb(),
        ];
    }
}
