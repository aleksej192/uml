<?php

namespace App\Services\Project\Import;
use App\Models\Project;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

class TempFilesService
{
    public function __construct(
        private Filesystem $filesystem
    )
    {
    }

    public function saveTempFile(Project $project, UploadedFile $file): string
    {
        $path = $project->id . '/' . time() . $file->getClientOriginalName();

        $this->filesystem->put($path, $file->getContent());

        return $path;
    }

    public function removeTempFile(string $path): void
    {
        $this->filesystem->delete($path);
    }

    public function getTempFileContent(string $path): string
    {
        return $this->filesystem->get($path);
    }
}
