<?php

namespace App\Dto\Project\Import;

class Parameter
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
    )
    {
    }

    public function toDb(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
        ];
    }
}
