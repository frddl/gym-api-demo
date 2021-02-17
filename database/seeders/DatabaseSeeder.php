<?php

namespace Database\Seeders;

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
        $this->call(TrainingSessionsSeeder::class);
        $this->call(TrainingSessionsInfoSeeder::class);
    }
}
