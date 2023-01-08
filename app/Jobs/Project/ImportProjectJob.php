<?php

namespace App\Jobs\Project;

use App\Models\Project;
use App\Services\Project\Import\Parsers\JavaParser;
use App\Services\Project\Import\PrepareNodesToDbService;
use App\Services\Project\Import\TempFilesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ImportProjectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Collection $paths,
        private Project $project,
    )
    {
    }

    public function handle(TempFilesService $tempFilesService, JavaParser $parser, PrepareNodesToDbService $prepareNodesToDbService)
    {
        $nodes = collect();

        foreach ($this->paths as $path) {
            $nodes = $nodes->push(...$parser->parse($tempFilesService->getTempFileContent($path)));
        }

        foreach ($this->paths as $path) {
            $tempFilesService->removeTempFile($path);
        }

        $this->project->setCompleteStatus();
        $this->project->schema = $prepareNodesToDbService->toDb($nodes);
        $this->project->save();
    }
}
