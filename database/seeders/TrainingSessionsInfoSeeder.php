<?php

namespace Database\Seeders;

use App\Models\Training\TrainingSession;
use App\Models\Training\TrainingSessionInfo;
use App\Models\Users\Client;
use Illuminate\Database\Seeder;

class TrainingSessionsInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $randomSession = TrainingSession::inRandomOrder()->limit(1)->first();
        TrainingSessionInfo::create([
           'client_id' => Client::first()->id,
           'training_session_id' => $randomSession->id,
        ]);
    }
}
