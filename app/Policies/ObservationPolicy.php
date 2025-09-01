<?php

namespace App\Policies;

use App\Models\Observation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ObservationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return (bool)$user;
    }

    public function view(User $user, Observation $observation): bool
    {
        return $observation->user->is($user);
    }

    public function create(User $user): bool
    {
        return (bool)$user;
    }

    public function update(User $user, Observation $observation): bool
    {
        return $observation->user->is($user);
    }

    public function delete(User $user, Observation $observation): bool
    {
        return $observation->user->is($user);
    }
}
