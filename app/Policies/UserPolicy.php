<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isTeacher() && $model->isStudent()) return true;
        if ($user->id === $model->id) return true;
        if ($user->isParent() && $model->studentProfile?->parent_id === $user->id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        if ($user->isAdmin()) return true;
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}