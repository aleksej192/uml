<?php

namespace App\UseCases\Project\Handlers;

use App\Jobs\Project\ImportProjectJob;
use App\Models\Project;
use App\Services\Project\Import\TempFilesService;
use App\UseCases\Project\Commands\ImportProjectCommand;
use Illuminate\Support\Facades\DB;

class ImportProjectHandler
{
    public function __construct(
        private TempFilesService $tempFilesService
    )
    {
    }

    public function __invoke(ImportProjectCommand $command): Project
    {
        $project = new Project();
        $project->name = $command->name;
        $project->setInProgressStatus();

        $command->user->projects()->save($project);

        $paths = collect();

        foreach ($command->files as $file) {
            $paths->push($this->tempFilesService->saveTempFile($project, $file));
        }

        dispatch(new ImportProjectJob($paths, $project));

        return $project;
    }
}
