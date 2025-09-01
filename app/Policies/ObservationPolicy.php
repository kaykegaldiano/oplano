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
        return $user->can('observations.view');
    }

    public function view(User $user, Observation $observation): bool
    {
        return $observation->user->is($user) && $user->can('observations.view');
    }

    public function create(User $user): bool
    {
        return $user->can('observations.create');
    }

    public function update(User $user, Observation $observation): bool
    {
        return $observation->user->is($user) && $user->can('observations.update');
    }

    public function delete(User $user, Observation $observation): bool
    {
        return $observation->user->is($user) && $user->can('observations.delete');
    }
}
