<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $fillable = [
        'jadwal_id',
        'pondok_id',
        'seri_id',
        'tanggal_distribusi',
        'jam_distribusi',
        'jumlah_karung_distribusi',
        'jumlah_kg_distribusi',
        'catatan',
        'foto_bukti',
        'foto_watermark',
        'user_id'
    ];
    protected $casts = ['tanggal_distribusi' => 'date'];

    public function jadwal()
    {
        return $this->belongsTo(Plan::class, 'jadwal_id');
    }

    public function pondok()
    {
        return $this->belongsTo(Foundation::class, 'pondok_id');
    }

    public function seri()
    {
        return $this->belongsTo(Series::class, 'seri_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}