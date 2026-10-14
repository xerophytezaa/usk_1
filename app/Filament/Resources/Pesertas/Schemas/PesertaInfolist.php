<?php

namespace App\Filament\Resources\Pesertas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PesertaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('skema_id')
                    ->numeric(),
                TextEntry::make('nik'),
                TextEntry::make('nama_lengkap'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('no_hp'),
                TextEntry::make('alamat'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
