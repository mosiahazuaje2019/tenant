<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::firstOrCreate(
            ['email' => 'admin@system.local'],
            ['name' => 'System Admin Tenant']
        );

        User::firstOrCreate(
            ['email' => 'admin@system.local'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('secret123'),
                'client_id' => $client->id,
            ]
        );
    }
}
