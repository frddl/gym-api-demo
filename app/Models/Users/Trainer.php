<?php

namespace App\Models\Users;

use App\Models\User;

class Trainer extends User
{
    protected $foreign_key = 'trainer_id';
}
