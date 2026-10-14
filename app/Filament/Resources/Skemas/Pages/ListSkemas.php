<?php

namespace App\Filament\Resources\Skemas\Pages;

use App\Filament\Resources\Skemas\SkemaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkemas extends ListRecords
{
    protected static string $resource = SkemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
