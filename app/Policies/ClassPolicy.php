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
        return $user->can('classes.view');
    }

    public function view(User $user, ClassModel $class): bool
    {
        if ($user->hasRole(['admin_global', 'customer_success'])) {
            return true;
        }

        if ($user->hasRole('monitor')) {
            return $user->monitoredClasses()->where('classes.id', $class->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('classes.create');
    }

    public function update(User $user, ClassModel $classModel): bool
    {
        return $user->can('classes.update');
    }

    public function delete(User $user, ClassModel $classModel): bool
    {
        return $user->can('classes.delete');
    }
}
