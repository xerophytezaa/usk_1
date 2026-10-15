<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Tables\Columns\Layout\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // 1. Admin & Super Admin selalu BISA login
        if ($this->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        // 2. Peserta BISA login HANYA jika datanya sudah diverifikasi (is_active = true)
        if ($this->hasRole('peserta')) {
            // Ambil data peserta terkait
            $peserta = $this->peserta;

            // Jika record peserta ada dan is_active bernilai true/1
            return $peserta && (bool) $peserta->is_active;
        }

        return false;
    }
}
