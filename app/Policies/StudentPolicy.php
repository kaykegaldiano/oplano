<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Student $student): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isCS();
    }

    public function update(User $user, Student $student): bool
    {
        return $user->isAdmin() || $user->isCS();
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->isAdmin();
    }
}
