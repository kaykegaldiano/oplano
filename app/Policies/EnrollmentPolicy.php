<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isCS() || $user->isMonitor();
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->isAdmin() || $user->isCS()) {
            return true;
        }

        return $user->isMonitor() && $user->monitoredClasses()->where('class_id', $enrollment->class_id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCS();
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($user->isAdmin() || $user->isCS()) {
            return true;
        }

        return $user->isMonitor() && $user->monitoredClasses()->where('class_id', $enrollment->class_id)->exists();
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin() || $user->isCS();
    }
}
