<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;

class GroupPolicy
{

    public function viewAny(User $user): bool
    {
        return true;
    }


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group): bool
    {
        return $this->isAdmin($user)
            || $this->isTeacherOfGroup($user, $group)
            || $this->isStudentOfGroup($user, $group);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        return $this->isAdmin($user)
            || $this->isTeacherOfGroup($user, $group)
            || $this->isStudentOfGroup($user, $group);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group): bool
    {
        return $this->isAdmin($user);
    }

    private function isAdmin(User $user): bool
    {
        return $user->hasRole(roles: 'admin');
    }

    private function isTeacherOfGroup(User $user, Group $group): bool
    {
        return $user->teacher && $user->teacher->groups->contains($group);
    }
    private function isStudentOfGroup(User $user, Group $group): bool
    {
        return $user->student && $user->student->groups->contains($group);
    }
}
