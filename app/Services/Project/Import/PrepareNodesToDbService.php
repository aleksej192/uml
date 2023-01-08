<?php

namespace App\Services\Project\Import;

use App\Dto\Project\Import\Node;
use Illuminate\Support\Collection;

class PrepareNodesToDbService
{
    /**
     * @param Collection|Node[] $nodes
     * @return array
     */
    public function toDb(Collection $nodes): array
    {
        $data = [
            'linkdata' => [],
            'nodedata' => [],
        ];

        foreach ($nodes as $node) {
            $data['nodedata'][] = $node->toDb();
        }

        return $data;
    }
}
