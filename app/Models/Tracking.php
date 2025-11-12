<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    /**
     * TAMBAHKAN BARIS INI
     * Ini mengizinkan semua bidang untuk diisi menggunakan ::create()
     */
    protected $guarded = [];

    /**
     * $casts ini juga penting untuk mengubah teks tanggal
     * menjadi objek tanggal (sudah ada di panduan sebelumnya)
     */
    protected $casts = [
        'security_start' => 'datetime',
        'security_end' => 'datetime',
        'loading_start' => 'datetime',
        'loading_end' => 'datetime',
        'ttb_start' => 'datetime',
        'ttb_end' => 'datetime',
    ];
}