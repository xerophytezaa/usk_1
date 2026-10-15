<?php

namespace App\Filament\Resources\Pesertas\Pages;

use App\Filament\Resources\Pesertas\PesertaResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Notification;

class ListPesertas extends ListRecords
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();

        // Jika login sebagai peserta dan belum diverifikasi admin
        if ($user?->hasRole('peserta') && ! $user->peserta?->is_active) {
            Notification::make()
                ->warning()
                ->title('Lengkapi Data & Berkas Anda!')
                ->body('Silakan klik tombol Edit pada data Anda untuk melengkapi NIK, Alamat, serta mengunggah KTP dan Ijazah agar dapat diverifikasi oleh Admin.')
                ->persistent()
                ->send();
        }
    }
}
