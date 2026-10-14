<?php

namespace App\Filament\Resources\Skemas\Pages;

use App\Filament\Resources\Skemas\SkemaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSkema extends ViewRecord
{
    protected static string $resource = SkemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
