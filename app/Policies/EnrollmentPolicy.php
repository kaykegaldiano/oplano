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
        return $user->can('enrollments.view');
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->hasRole(['admin_global', 'customer_success'])) {
            return true;
        }

        if ($user->hasRole('monitor')) {
            return $user->monitoredClasses()->where('classes.id', $enrollment->class_id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('enrollments.create');
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        if ($user->hasRole(['admin_global', 'customer_success'])) {
            return true;
        }

        if ($user->hasRole('monitor') && $user->can('enrollments.complete')) {
            return $user->monitoredClasses()->where('classes.id', $enrollment->class_id)->exists();
        }

        return false;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->can('enrollments.delete');
    }
}
