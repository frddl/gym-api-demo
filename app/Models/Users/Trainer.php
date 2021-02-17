<?php

namespace App\Models\Users;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trainer extends User
{
    protected $foreign_key = 'trainer_id';

    public function sessionsForWeek(): HasMany
    {
        $from = date('Y-m-d');
        $to = Carbon::now()->add('days', 7)->format('Y-m-d');
        return $this->sessions()->where('date', '>=', $from)->where('date', "<=", $to);
    }
}
