<?php

namespace App\Models\Users;

use App\Models\User;

class Client extends User
{
    protected $foreign_key = 'client_id';
    protected $session_model = \App\Models\Training\TrainingSessionInfo::class;
}
