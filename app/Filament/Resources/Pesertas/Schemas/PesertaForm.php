<?php

namespace App\Filament\Resources\Pesertas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PesertaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('skema_id')
                    ->required()
                    ->numeric(),
                TextInput::make('nik')
                    ->required(),
                TextInput::make('nama_lengkap')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('no_hp')
                    ->required(),
                TextInput::make('alamat')
                    ->required(),
            ]);
    }
}
