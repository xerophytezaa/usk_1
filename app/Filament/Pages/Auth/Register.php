<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Components\Component;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    protected function getNameFormComponent(): Component
    {
        return parent::getNameFormComponent()
            ->maxLength(50)
            ->regex('/^[a-zA-Z\s]+$/')
            ->validationMessages([
                'regex' => 'Nama hanya boleh berupa huruf dan spasi, tidak boleh ada angka atau karakter khusus!',
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        /** @var User $user */
        $user = parent::handleRegistration($data);

        $user->assignRole('peserta');

        $user->peserta()->create([
            'nama_lengkap' => $user->name,
        ]);

        return $user;
    }
}