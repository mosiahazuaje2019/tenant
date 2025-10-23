<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Create new client and user.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            // Create client
            $client = Client::create([
                'name'  => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
            ]);

            // Create user
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'client_id' => $client->id,
            ]);

            return response()->json([
                'client' => $client,
                'user'   => $user->only(['id', 'name', 'email', 'client_id']),
            ], 201);
        });
    }
}
