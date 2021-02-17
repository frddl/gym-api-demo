<?php

namespace App\Models\Training;

use App\Models\Users\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSessionInfo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id', 'training_session_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'training_session_id'];
    protected $with = ['session'];

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function session(): BelongsTo {
        return $this->belongsTo(TrainingSession::class, 'training_session_id', 'id');
    }

    public function scopeByClient(Builder $query, int $clientId): Builder {
        return $query->where('client_id', $clientId);
    }
}
