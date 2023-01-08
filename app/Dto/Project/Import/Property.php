<?php

namespace App\Dto\Project\Import;

class Property
{
    public function __construct(
        public readonly string $name,
        public readonly string $visibility,
        public readonly ?string $type,
    )
    {
    }

    public function toDb(): array
    {
        return [
            'visibility' => $this->visibility,
            'type' => $this->type,
            'name' => $this->name,
        ];
    }
}
