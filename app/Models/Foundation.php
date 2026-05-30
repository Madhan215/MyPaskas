<?php

namespace App\Models;

use App\Models\Distribution;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    protected $fillable = ['nama', 'alamat', 'jumlah_santri', 'jumlah_pengasuh', 'penanggung_jawab', 'no_hp', 'is_active', 'google_maps_url'];

    public function jadwals()
    {
        return $this->hasMany(Plan::class, 'pondok_id');
    }

    public function aktivitas()
    {
        return $this->hasMany(Distribution::class, 'pondok_id');
    }

    // Total penerima = santri + pengasuh
    public function getTotalPenerimaAttribute(): int
    {
        return $this->jumlah_santri + $this->jumlah_pengasuh;
    }

    // Hitung jatah: 1 orang = 1 kg
    public function getJatahKgAttribute(): int
    {
        return $this->total_penerima;
    }

    public function getJatahKarungAttribute(): float
    {
        return ceil($this->jatah_kg / 10);
    }
}