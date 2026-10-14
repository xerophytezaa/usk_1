<?php

namespace App\Filament\Resources\Skemas;

use App\Filament\Resources\Skemas\Pages\CreateSkema;
use App\Filament\Resources\Skemas\Pages\EditSkema;
use App\Filament\Resources\Skemas\Pages\ListSkemas;
use App\Filament\Resources\Skemas\Pages\ViewSkema;
use App\Filament\Resources\Skemas\Schemas\SkemaForm;
use App\Filament\Resources\Skemas\Schemas\SkemaInfolist;
use App\Filament\Resources\Skemas\Tables\SkemasTable;
use App\Models\Skema;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\TextEntry;

class SkemaResource extends Resource
{
    protected static ?string $model = Skema::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('kode_skema')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Kode Skema'),
                Forms\Components\TextInput::make('nama_skema')
                    ->required()
                    ->label('Nama Skema'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_skema')
                    ->searchable()
                    ->sortable()
                    ->label('Kode Skema'),
                Tables\Columns\TextColumn::make('nama_skema')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Skema'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Dibuat Pada'),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->modalHeading('Detail Skema')
                    ->modalWidth('md'),
                Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Berhasil')
                            ->body('Data berhasil diperbarui.')
                    ),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kode_skema')
                    ->label('Kode Skema'),
                TextEntry::make('nama_skema')
                    ->label('Nama Skema'),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSkemas::route('/'),
            'create' => CreateSkema::route('/create'),
            //'view' => ViewSkema::route('/{record}'),
            // 'edit' => EditSkema::route('/{record}/edit'),
        ];
    }
}
