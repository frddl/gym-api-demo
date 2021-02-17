<?php

namespace Database\Seeders;

use App\Models\Training\TrainingSession;
use App\Models\Users\Trainer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TrainingSessionsSeeder extends Seeder
{
    public $data = [
        [
            'start_time' => '9:00',
            'end_time' => '10:00',
        ],
        [
            'start_time' => '12:00',
            'end_time' => '13:00',
        ],
        [
            'start_time' => '15:00',
            'end_time' => '16:00',
        ],
        [
            'start_time' => '18:00',
            'end_time' => '19:00',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->data as $instance) {
            TrainingSession::create([
                'date' => Carbon::today(),
                'start_time' => $instance['start_time'],
                'end_time' => $instance['end_time'],
                'trainer_id' => Trainer::first()->id,
            ]);
        }
    }
}
