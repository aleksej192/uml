<?php

namespace App\UseCases\Project\Commands;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ImportProjectCommand
{
    /**
     * @param User $user
     * @param string $name
     * @param array|UploadedFile[] $files
     */
    public function __construct(
        public readonly User $user,
        public readonly string $name,
        public readonly array $files,
    )
    {
    }
}
