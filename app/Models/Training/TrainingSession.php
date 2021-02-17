<?php

namespace App\Models\Training;

use App\Models\Users\Trainer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'date', 'start_time', 'end_time', 'trainer_id'
    ];

    public function trainer(): BelongsTo {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'id');
    }

    public function sessionInfo(): HasMany {
        return $this->hasMany(TrainingSessionInfo::class, 'training_session_id', 'id');
    }
}
