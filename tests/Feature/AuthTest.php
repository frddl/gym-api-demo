<?php

namespace Tests\Feature;

use App\Models\Users\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for client login
     *
     * @return void
     */
    public function testUsers()
    {
        $password = Str::random();
        $client = Client::factory()->create(['password' => Hash::make($password)]);

        // incorrect login
        $body = ['email' => $client->email, 'password' => $password];
        $response = $this->post(route('api.login', ['user' => 'trainers']), $body);
        $response->assertStatus(401);

        // correct login
        $response = $this->post(route('api.login', ['user' => 'clients']), $body);
        $response->assertStatus(200);
        $token = $response->json('access_token');
    }

    /**
     * Feature test for checking trainers
     *
     * @return void
     */
    public function testGuards()
    {
        $password = Str::random();
        $client = Client::factory()->create(['password' => Hash::make($password)]);
        $body = ['email' => $client->email, 'password' => $password];

        $response = $this->post(route('api.login', ['user' => 'clients']), $body);
        $response->assertStatus(200);
        $token = $response->json('access_token');

        // incorrect guard
        $response = $this->get(route('api.me', ['user' => 'trainers']),
            ['headers' => ["Authorization" => "Bearer $token"]]
        );
        $response->assertStatus(401);

        // correct guard
        $response = $this->get(route('api.me', ['user' => 'clients']),
            ['headers' => ["Authorization" => "Bearer $token"]]
        );
        $response->assertStatus(200);
    }
}
