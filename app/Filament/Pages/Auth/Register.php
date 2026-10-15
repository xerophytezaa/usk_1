<?php

namespace App\Filament\Pages\Auth;

use App\Models\Peserta;
use App\Models\Skema;
use App\Models\User;
use Filament\Auth\Http\Responses\RegistrationResponse;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Schemas\Components\Component;


class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Field Akun Default Filament
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),

                // Field Tambahan Data Peserta
                Select::make('skema_id')
                    ->label('Skema Sertifikasi')
                    ->options(Skema::pluck('nama_skema', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->mask('9999999999999999')
                    ->length(16)
                    ->regex('/^[1-9][0-9]{15}$/')
                    ->validationMessages(['regex' => 'NIK harus berupa 16 digit angka yang valid.'])
                    ->unique('pesertas', 'nik'),

                TextInput::make('no_hp')
                    ->label('No. HP / WA')
                    ->tel()
                    ->required()
                    ->minLength(7)
                    ->maxLength(15)
                    ->regex('/^(\+62|62|0)8[1-9][0-9]{7,11}$/')
                    ->validationMessages([
                        'regex' => 'Nomor HP tidak valid. Gunakan format Indonesia (contoh: 081234567890).',
                    ]),

                Textarea::make('alamat')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->columnSpanFull(),

                // File Upload Berkas Persyaratan
                FileUpload::make('file_ktp')
                    ->label('KTP')
                    ->directory('peserta-ktp')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->required(),

                FileUpload::make('file_ijazah')
                    ->label('Ijazah Terakhir')
                    ->directory('peserta-ijazah')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->required(),

                FileUpload::make('file_sertifikat')
                    ->label('Sertifikat Pendukung / Pelatihan (Opsional)')
                    ->directory('peserta-sertifikat')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->nullable(),
            ]);
    }
    
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
        return DB::transaction(function () use ($data) {
            // 1. Simpan User
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
            ]);

            // 2. Assign Role Shield 'peserta'
            $user->assignRole('peserta');

            // 3. Simpan Data Peserta
            Peserta::create([
                'user_id'         => $user->id,
                'skema_id'        => $data['skema_id'],
                'nik'             => $data['nik'],
                'nama_lengkap'    => $data['name'],
                'email'           => $data['email'],
                'no_hp'           => $data['no_hp'],
                'alamat'          => $data['alamat'],
                'file_ktp'        => $data['file_ktp'] ?? null,
                'file_ijazah'     => $data['file_ijazah'] ?? null,
                'file_sertifikat' => $data['file_sertifikat'] ?? null,
                'status'          => 'pending',
                'is_active'       => false, // Kunci login, belum diverifikasi admin
            ]);

            return $user;
        });
    }
    public function register(): ?RegistrationResponse
    {
        parent::register();

        // Paksa Logout
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        // Kirim Pesan Sukses & Redirect
        session()->flash('success', 'Pendaftaran berhasil! Data & berkas Anda sedang diverifikasi oleh Admin. Silakan tunggu verifikasi sebelum login.');

        redirect()->to('/');

        return null;
    }
}