<?php

namespace Database\Seeders;

use App\Models\Users\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public $data = [
        [
            'name' => 'Demo Client',
            'email' => 'client@email.com',
            'password' => 'client'
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
            Client::create([
                'name' => $instance['name'],
                'email' => $instance['email'],
                'password' => Hash::make($instance['password'])
            ]);
        }
    }
}
