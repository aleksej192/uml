<?php

namespace App\Services\Project\Import\Parsers;

use App\Dto\Project\Import\Method;
use App\Dto\Project\Import\Node;
use App\Dto\Project\Import\Parameter;
use App\Dto\Project\Import\Property;
use Illuminate\Support\Collection;

class JavaParser implements Parser
{
    public function parse(string $content): Collection
    {
        $nodes = collect();

        $nodes->push($this->parseNode($content));

        return $nodes;
    }

    private function parseNode(string $content): Node
    {
        preg_match_all('/\s*((public|private)\s+)?(class|interface)\s+(\w+)\s+((extends|implements*)(\s+\w+( ,\w+)?))?\s*{/ims', $content, $matches);

        $nodeType = $matches[3][0];
        $nodeName = $matches[4][0];
        $nodeLinkType = $matches[5][0];
        $nodeLink = $matches[6][0];

        $methods = $this->parseMethods($content);
        $properties = $this->parseProperties($content);

        return new Node(
            $nodeName,
            $nodeType,
            $methods,
            $properties,
        );
    }

    private function parseMethods(string $content): Collection
    {
        preg_match_all('/(public|private|protected)\s*(\w*)\s*(\w*)\((.*)\)\s*{/msU', $content, $matches);

        $methods = collect();

        for ($i = 0; $i < count($matches[0]); $i++) {
            $parameters = collect(explode(',', $matches[4][$i]))->filter(fn($item) => !empty($item));

            $methods->push(new Method(
                $matches[1][$i],
                $matches[3][$i],
                empty($matches[2][$i]) ? null : $matches[2][$i],
                $parameters->map(function ($item) {
                    $item = explode(' ', trim($item));

                    return new Parameter($item[1], $item[0]);
                }),
            ));
        }

        return $methods;
    }

    private function parseProperties(string $content): Collection
    {
        preg_match_all('/(public|private|protected)\s*(\w*\s?)((\w*\s?)(;|=))/msU', $content, $matches);

        $properties = collect();

        for ($i = 0; $i < count($matches[0]); $i++) {
            $name = trim($matches[4][$i]);
            $visibility = trim($matches[1][$i]);
            $type = trim($matches[2][$i]);

            $properties->push(new Property(
                $name,
                $visibility,
                empty($type) ? null : $type,
            ));
        }

        return $properties;
    }
}
