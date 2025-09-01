<?php

namespace App\Policies;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isCS() || $user->isMonitor();
    }

    public function view(User $user, ClassModel $class): bool
    {
        if ($user->isAdmin() || $user->isCS()) return true;

        return $user->isMonitor()
            && $user->monitoredClasses()->where('classes.id', $class->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCS();
    }

    public function update(User $user, ClassModel $classModel): bool
    {
        return $user->isAdmin() || $user->isCS();
    }

    public function delete(User $user, ClassModel $classModel): bool
    {
        return $user->isAdmin();
    }
}
