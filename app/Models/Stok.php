<?php

namespace App\Models;

use App\Models\Distribution;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $fillable = ['tanggal', 'jumlah_karung', 'berat_kg', 'sumber', 'keterangan', 'user_id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hitung total stok saat ini (masuk - keluar)
    public static function totalStokKarung(): int
    {
        $masuk = self::sum('jumlah_karung');
        $keluar = Distribution::sum('jumlah_karung_distribusi');
        return max(0, $masuk - $keluar);
    }

    public static function totalStokKg(): int
    {
        $masuk = self::sum('berat_kg');
        $keluar = Distribution::sum('jumlah_kg_distribusi');
        return max(0, $masuk - $keluar);
    }
}