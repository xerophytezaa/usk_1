<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PesertaPolicy
{
    use HandlesAuthorization;

    public function before(AuthUser $authUser, string $ability): ?bool
    {
        /** @var User $user */
        $user = $authUser;

        // if ($ability === 'create') {
        //     return null;
        // }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return null;
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return true;
    }

    public function view(AuthUser $authUser, Peserta $peserta): bool
    {
        return $authUser->id === $peserta->user_id || $authUser->can('View:Peserta');
    }

    public function create(AuthUser $authUser): bool
    {
        return false;
        // return $authUser->can('Create:Peserta');
    }

    public function update(AuthUser $authUser, Peserta $peserta): bool
    {
        return $authUser->id === $peserta->user_id || $authUser->can('Update:Peserta');
    }

    public function delete(AuthUser $authUser, Peserta $peserta): bool
    {
        return $authUser->can('Delete:Peserta');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Peserta');
    }

    public function restore(AuthUser $authUser, Peserta $peserta): bool
    {
        return $authUser->can('Restore:Peserta');
    }

    public function forceDelete(AuthUser $authUser, Peserta $peserta): bool
    {
        return $authUser->can('ForceDelete:Peserta');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Peserta');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Peserta');
    }

    public function replicate(AuthUser $authUser, Peserta $peserta): bool
    {
        return $authUser->can('Replicate:Peserta');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Peserta');
    }

}