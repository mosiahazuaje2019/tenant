<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_issues_a_token_with_valid_credentials(): void
    {
        $client = Client::factory()->create();
        User::factory()->create([
            'email'     => 'demo@example.com',
            'password'  => Hash::make('secret123'),
            'client_id' => $client->id,
        ]);

        $this->postJson('/api/auth/token', [
            'email' => 'demo@example.com',
            'password' => 'secret123',
        ])
            ->assertOk()
            ->assertJsonStructure(['token']);
    }

    public function test_rejects_invalid_credentials(): void
    {
        $client = Client::factory()->create();
        User::factory()->create([
            'email'     => 'demo@example.com',
            'password'  => Hash::make('secret123'),
            'client_id' => $client->id,
        ]);

        $this->postJson('/api/auth/token', [
            'email' => 'demo@example.com',
            'password' => 'wrong',
        ])->assertStatus(422);
    }
}
