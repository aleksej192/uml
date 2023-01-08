<?php

namespace App\Services\Project\Import\Parsers;

use Illuminate\Support\Collection;

interface Parser
{
    public function parse(string $content): Collection;
}
