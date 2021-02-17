<?php

namespace Database\Seeders;

use App\Models\Users\Trainer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    public $data = [
        [
            'name' => 'Demo Trainer',
            'email' => 'trainer@email.com',
            'password' => 'trainer'
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
            Trainer::create([
                'name' => $instance['name'],
                'email' => $instance['email'],
                'password' => Hash::make($instance['password'])
            ]);
        }
    }
}
