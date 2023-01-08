<?php

namespace App\Models;

use App\Enums\Project\ProjectImportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Project
 */
class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setInProgressStatus(): void
    {
        $this->import_status = ProjectImportStatus::STATUS_IN_PROGRESS->value;
    }

    public function setCompleteStatus(): void
    {
        $this->import_status = ProjectImportStatus::STATUS_COMPLETE->value;
    }
}
