<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Simpanan extends Model
{
    protected $table = 'simpanans';

    protected $fillable = [
        'anggota_id',
        'nomor_transaksi',
        'tanggal',
        'jumlah',
        'jenis',
        'keterangan',
    ];

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}