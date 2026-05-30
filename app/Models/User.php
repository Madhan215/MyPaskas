<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'is_active', 'foto_profil', 'pondok_id'];
    protected $hidden = ['password', 'remember_token'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isOta(): bool
    {
        return $this->role === 'ota';
    }
    public function isPaskas(): bool
    {
        return $this->role === 'paskas';
    }
    public function isPondok(): bool
    {
        return $this->role === 'pondok';
    }

    /**
     * Hubungan antara User (akun pondok) dengan data Pondok.
     */
    public function pondok()
    {
        // Hubungan One-to-One atau Many-to-One tergantung struktur DB Anda.
        // Jika di tabel pondoks ada kolom user_id, gunakan hasOne:
        return $this->hasOne(Foundation::class, 'user_id');

        // ATAU jika di tabel users ada kolom pondok_id, gunakan belongsTo:
        // return $this->belongsTo(Pondok::class, 'pondok_id');
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'ota' => 'OTA / Donatur',
            'paskas' => 'Paskas / Penyalur',
            'pondok' => 'Pondok',
            default => $this->role,
        };
    }
}