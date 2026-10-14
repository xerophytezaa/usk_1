<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skema extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'id',
        'kode_skema',
        'nama_skema',
    ];

    public function pesertas(): HasMany{
        return $this->hasMany(Peserta::class);
    }
}
