<?php

namespace App\Models\Training;

use App\Models\Users\Trainer;
use Illuminate\Database\Eloquent\Builder;
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

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'date', 'trainer_id'];
    protected $appends = ['is_free'];
    protected $with = ['trainer'];

    public function trainer(): BelongsTo {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'id');
    }

    public function sessionInfo(): HasMany {
        return $this->hasMany(TrainingSessionInfo::class, 'training_session_id', 'id');
    }

    public function scopeByTrainer(Builder $query, int $trainerId): Builder {
        return $query->where('trainer_id', $trainerId);
    }

    public function scopeByDate(Builder $query, string $date): Builder {
        return $query->where('date', $date);
    }

    public function getIsFreeAttribute() {
        return !$this->sessionInfo()->count();
    }
}
