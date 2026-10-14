<?php

namespace App\Filament\Resources\Pesertas;

use App\Filament\Resources\Pesertas\Pages\CreatePeserta;
use App\Filament\Resources\Pesertas\Pages\EditPeserta;
use App\Filament\Resources\Pesertas\Pages\ListPesertas;
use App\Filament\Resources\Pesertas\Pages\ViewPeserta;
use App\Models\Peserta;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('skema_id')
                    ->relationship('skema', 'nama_skema')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Skema Sertifikasi'),
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->mask('9999999999999999')
                    ->length(16)
                    ->regex('/^[1-9][0-9]{15}$/')
                    ->validationMessages(['regex' => 'NIK harus berupa 16 digit angka yang valid.'])
                    ->unique(ignoreRecord: true)
                    ->label('NIK'),
                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->minLength(2)
                    ->maxLength(50)
                    ->regex('/^[a-zA-Z\s\'\.-]+$/')
                    ->validationMessages(['regex' => 'Nama hanya boleh memuat huruf, spasi, titik, dan tanda petik.'])
                    ->label('Nama Lengkap'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(150)
                    ->regex('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/')
                    ->validationMessages([
                        'regex' => 'Format email tidak valid (contoh: nama@domain.com).',
                    ])
                    ->label('Email'),
                Forms\Components\TextInput::make('no_hp')
                    ->tel()
                    ->required()
                    ->minLength(10)
                    ->maxLength(14)
                    ->regex('/^(\+62|62|0)8[1-9][0-9]{7,11}$/')
                    ->validationMessages([
                        'regex' => 'Nomor HP tidak valid. Gunakan format Indonesia (contoh: 081234567890 atau +6281234567890).',
                    ])
                    ->label('No. HP / WA'),
                Forms\Components\Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull()
                    ->label('Alamat Lengkap'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->label('NIK')
                    ->formatStateUsing(fn($state) => number_format((float)$state, 0, '', '')),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                Tables\Columns\TextColumn::make('skema.nama_skema')
                    ->searchable()
                    ->sortable()
                    ->label('Skema'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP'),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->modalHeading('Detail Peserta')
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
                TextEntry::make('skema.nama_skema')
                    ->label('Skema Sertifikasi'),
                TextEntry::make('nik')
                    ->label('NIK'),
                TextEntry::make('nama_lengkap')
                    ->label('Nama Lengkap'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('no_hp')
                    ->label('No. HP / WA'),
                TextEntry::make('alamat')
                    ->label('Alamat Lengkap')
                    ->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if($user->hasRole(['super_admin', 'admin'])){
            return $query;
        }
        return $query->where('user_id', $user->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPesertas::route('/'),
            'create' => CreatePeserta::route('/create'),
            // 'view' => ViewPeserta::route('/{record}'),
            // 'edit' => EditPeserta::route('/{record}/edit'),
        ];
    }
}
