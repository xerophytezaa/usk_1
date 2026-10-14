<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Skema;
use Illuminate\Auth\Access\HandlesAuthorization;

class SkemaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Skema');
    }

    public function view(AuthUser $authUser, Skema $skema): bool
    {
        return $authUser->can('View:Skema');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Skema');
    }

    public function update(AuthUser $authUser, Skema $skema): bool
    {
        return $authUser->can('Update:Skema');
    }

    public function delete(AuthUser $authUser, Skema $skema): bool
    {
        return $authUser->can('Delete:Skema');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Skema');
    }

    public function restore(AuthUser $authUser, Skema $skema): bool
    {
        return $authUser->can('Restore:Skema');
    }

    public function forceDelete(AuthUser $authUser, Skema $skema): bool
    {
        return $authUser->can('ForceDelete:Skema');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Skema');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Skema');
    }

    public function replicate(AuthUser $authUser, Skema $skema): bool
    {
        return $authUser->can('Replicate:Skema');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Skema');
    }

}