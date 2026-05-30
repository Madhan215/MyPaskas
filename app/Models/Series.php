<?php

namespace App\Models;

use App\Models\Distribution;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $fillable = ['nama', 'bulan', 'tahun', 'tanggal_mulai', 'tanggal_selesai', 'status', 'keterangan', 'user_id'];
    protected $casts = ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Plan::class, 'seri_id');
    }

    public function aktivitas()
    {
        return $this->hasMany(Distribution::class, 'seri_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'aktif' => 'success',
            'selesai' => 'primary',
            default => 'secondary',
        };
    }

    public function getTotalKarungRencanaAttribute(): int
    {
        return $this->jadwals()->sum('jumlah_karung');
    }

    public function getTotalKgRencanaAttribute(): int
    {
        return $this->jadwals()->sum('jumlah_kg');
    }

    public function getTotalRealisasiKarungAttribute(): int
    {
        return $this->aktivitas()->sum('jumlah_karung_distribusi');
    }

    public function getProgressAttribute(): int
    {
        $total = $this->jadwals()->count();
        if ($total === 0)
            return 0;
        $selesai = $this->jadwals()->where('status', 'selesai')->count();
        return (int) (($selesai / $total) * 100);
    }
}