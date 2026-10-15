<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peserta extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'id',
        'user_id',
        'skema_id',
        'nik',
        'nama_lengkap',
        'email',
        'no_hp',
        'alamat',
        'status',
        'file_ktp',
        'file_ijazah',
        'file_sertifikat',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skema(): BelongsTo
    {
        return $this->belongsTo(Skema::class);
    }
}
