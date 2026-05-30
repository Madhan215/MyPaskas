<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['seri_id', 'pondok_id', 'tanggal', 'jumlah_karung', 'jumlah_kg', 'petugas', 'status', 'catatan'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function seri()
    {
        return $this->belongsTo(Series::class, 'seri_id');
    }

    public function pondok()
    {
        return $this->belongsTo(Foundation::class, 'pondok_id');
    }

    public function aktivitas()
    {
        return $this->hasOne(Distribution::class, 'jadwal_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum' => 'Belum',
            'selesai' => 'Selesai',
            'ditunda' => 'Ditunda',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'belum' => 'warning',
            'selesai' => 'success',
            'ditunda' => 'danger',
            default => 'secondary',
        };
    }
}