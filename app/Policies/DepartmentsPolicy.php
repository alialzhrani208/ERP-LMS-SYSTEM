<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Departments;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DepartmentsPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Departments');
    }

    public function view(AuthUser $authUser, Departments $departments): bool
    {
        return $authUser->can('View:Departments');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Departments');
    }

    public function update(AuthUser $authUser, Departments $departments): bool
    {
        return $authUser->can('Update:Departments');
    }

    public function delete(AuthUser $authUser, Departments $departments): bool
    {
        return $authUser->can('Delete:Departments');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Departments');
    }

    public function restore(AuthUser $authUser, Departments $departments): bool
    {
        return $authUser->can('Restore:Departments');
    }

    public function forceDelete(AuthUser $authUser, Departments $departments): bool
    {
        return $authUser->can('ForceDelete:Departments');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Departments');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Departments');
    }

    public function replicate(AuthUser $authUser, Departments $departments): bool
    {
        return $authUser->can('Replicate:Departments');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Departments');
    }
}
