<?php

namespace Database\Seeders;

use App\Models\Training\TrainingSession;
use App\Models\Training\TrainingSessionInfo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClientSeeder::class);
        $this->call(TrainerSeeder::class);
        $this->call(TrainingSession::class);
        $this->call(TrainingSessionInfo::class);
    }
}
