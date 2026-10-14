<?php

namespace App\Filament\Resources\Pesertas\Pages;

use App\Filament\Resources\Pesertas\PesertaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPeserta extends EditRecord
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    // protected function getSaveNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->title('Peserta berhasil diperbarui.')
    //         ->success()
    //         ->body('Perubahan data peserta telah disimpan.')
    //         ->send();
    // }
}
