<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = ['password', 'created_at', 'updated_at', 'deleted_at'];

    protected $foreign_key = 'id';
    protected $session_model = \App\Models\Training\TrainingSession::class;

    public function sessions(): HasMany
    {
        return $this->hasMany($this->session_model, $this->foreign_key, 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
