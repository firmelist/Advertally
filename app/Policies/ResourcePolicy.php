<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Maps CRUD abilities onto "{area}.view|manage|delete" permissions. Super admins pass via Gate::before.
 */
abstract class ResourcePolicy
{
    /** Permission area, e.g. "services". */
    protected string $area;

    public function viewAny(User $user): bool
    {
        return $user->hasPermission("{$this->area}.view");
    }

    public function view(User $user, Model $model): bool
    {
        return $user->hasPermission("{$this->area}.view");
    }

    public function create(User $user): bool
    {
        return $user->hasPermission("{$this->area}.manage");
    }

    public function update(User $user, Model $model): bool
    {
        return $user->hasPermission("{$this->area}.manage");
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->hasPermission("{$this->area}.delete");
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission("{$this->area}.delete");
    }

    public function reorder(User $user): bool
    {
        return $user->hasPermission("{$this->area}.manage");
    }
}
