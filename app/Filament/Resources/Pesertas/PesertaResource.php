<?php

namespace App\Filament\Resources\Pesertas;

use App\Filament\Resources\Pesertas\Pages\CreatePeserta;
use App\Filament\Resources\Pesertas\Pages\ListPesertas;
use App\Models\Peserta;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
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
                // Section 1: Data Utama Peserta
                Section::make('Data Calon Peserta')
                    ->description('Informasi identitas pendaftar uji kompetensi')
                    ->schema([
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
                            ->maxLength(100)
                            ->regex('/^[a-zA-Z\s\'\.-]+$/')
                            ->validationMessages(['regex' => 'Nama hanya boleh memuat huruf, spasi, titik, dan tanda petik.'])
                            ->label('Nama Lengkap'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(150)
                            ->label('Email'),

                        Forms\Components\TextInput::make('no_hp')
                            ->tel()
                            ->required()
                            ->minLength(7)
                            ->maxLength(15)
                            ->regex('/^(\+62|62|0)8[1-9][0-9]{7,11}$/')
                            ->validationMessages([
                                'regex' => 'Nomor HP tidak valid. Gunakan format Indonesia (contoh: 081234567890).',
                            ])
                            ->label('No. HP / WA'),

                        Forms\Components\Textarea::make('alamat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->label('Alamat Lengkap'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                // Section 2: Berkas Persyaratan (Dokumen)
                Section::make('Berkas Persyaratan')
                    ->description('Unggah dokumen pendukung sertifikasi (Format: PDF / JPG / PNG, Max 2MB)')
                    ->schema([
                        Forms\Components\FileUpload::make('file_ktp')
                            ->label('KTP')
                            ->directory('peserta-ktp')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(2048)
                            ->openable()
                            ->downloadable()
                            ->required(),

                        Forms\Components\FileUpload::make('file_ijazah')
                            ->label('Ijazah Terakhir')
                            ->directory('peserta-ijazah')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(2048)
                            ->openable()
                            ->downloadable()
                            ->required(),

                        Forms\Components\FileUpload::make('file_sertifikat')
                            ->label('Sertifikat Pendukung / Pelatihan')
                            ->directory('peserta-sertifikat')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(2048)
                            ->openable()
                            ->downloadable()
                            ->nullable(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                // Section 3: Verifikasi Admin & Status (Hanya Muncul Untuk Admin & Super Admin)
                Section::make('Verifikasi & Status Admin')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Verifikasi & Izinkan Login Peserta')
                            ->helperText('Aktifkan tombol ini jika berkas NIK, KTP, dan Ijazah sudah valid.')
                            ->disabled(fn() => !auth()->user()?->hasRole(['super_admin', 'admin']))
                            ->dehydrated(true),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending / Menunggu Verifikasi',
                                'terverifikasi' => 'Terverifikasi',
                                'lulus' => 'Lulus Uji Kompetensi',
                                'tidak_lulus' => 'Tidak Lulus',
                            ])
                            ->default('pending')
                            ->required()
                            ->disabled(fn() => !auth()->user()?->hasRole(['super_admin', 'admin']))
                            ->dehydrated(true)
                            ->label('Status Pendaftaran'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    // MENYEMBUNYIKAN SECTION INI JIKA USER LOGIN SEBAGAI PESERTA
                    ->visible(fn() => auth()->user()?->hasRole(['super_admin', 'admin'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Akses Login')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'terverifikasi' => 'info',
                        'lulus' => 'success',
                        'tidak_lulus' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'terverifikasi' => 'Terverifikasi',
                        'lulus' => 'Lulus Uji',
                        'tidak_lulus' => 'Tidak Lulus',
                        default => ucfirst($state),
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nik')
                    ->searchable()
                    ->label('NIK')
                    ->formatStateUsing(fn($state) => number_format((float) $state, 0, '', '')),

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
                    ->modalWidth('5xl'),

                // MEMPERLEBAR UKURAN MODAL EDIT AGAR TAMPILAN TIDAK BERANTAKAN
                Actions\EditAction::make()
                    ->modalHeading('Edit Data Peserta')
                    ->modalWidth('5xl')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Berhasil')
                            ->body('Data berhasil diperbarui.')
                    ),

                Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'terverifikasi' => 'Terverifikasi',
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                    ])
                    ->label('Filter Status'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'terverifikasi' => 'info',
                        'lulus' => 'success',
                        'tidak_lulus' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status Pendaftaran'),

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

        if ($user?->hasRole(['super_admin', 'admin'])) {
            return $query;
        }

        return $query->where('user_id', $user?->id);
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