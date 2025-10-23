<?php

namespace App\Policies;

use App\Models\User;

class ClientPolicy
{
    public function viewOrders(User $user, int $clientId): bool
    {
        return (int) $user->client_id === (int) $clientId;
    }
}
