<?php

namespace App\Enums\Project;
enum ProjectImportStatus : string
{
    case STATUS_IN_PROGRESS = 'in_progress';

    case STATUS_COMPLETE = 'complete';
}
