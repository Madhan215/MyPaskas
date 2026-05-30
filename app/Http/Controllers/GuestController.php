<?php

namespace App\Http\Controllers;

use App\Models\{Foundation, Series, Distribution};

class GuestController extends Controller
{
    public function index()
    {
        // Statistik publik
        $totalPondok = Foundation::where('is_active', true)->count();
        $totalSantri = Foundation::where('is_active', true)->sum('jumlah_santri');
        $totalKgSelesai = Distribution::sum('jumlah_kg_distribusi');
        $totalSeri = Series::where('status', 'selesai')->count();
        $seriAktif = Series::where('status', 'aktif')->first();

        // Aktivitas terbaru (publik)
        $aktivitasTerbaru = Distribution::with(['pondok', 'user'])
            ->latest()
            ->limit(6)
            ->get();

        // Daftar pondok dengan total terima
        $pondoks = Foundation::where('is_active', true)
            ->withCount('aktivitas')
            ->withSum('aktivitas', 'jumlah_kg_distribusi')
            ->orderByDesc('aktivitas_sum_jumlah_kg_distribusi')
            ->get();

        // Progress seri aktif
        $progressSeri = null;
        if ($seriAktif) {
            $total = $seriAktif->jadwals()->count();
            $selesai = $seriAktif->jadwals()->where('status', 'selesai')->count();
            $progressSeri = $total > 0 ? round(($selesai / $total) * 100) : 0;
        }

        return view('guest.index', compact(
            'totalPondok',
            'totalSantri',
            'totalKgSelesai',
            'totalSeri',
            'seriAktif',
            'aktivitasTerbaru',
            'pondoks',
            'progressSeri'
        ));
    }

    public function pondokDetail(Foundation $pondok)
    {
        $aktivitas = Distribution::with(['seri'])
            ->where('pondok_id', $pondok->id)
            ->latest()
            ->get();

        $totalKg = $aktivitas->sum('jumlah_kg_distribusi');
        $totalKarung = $aktivitas->sum('jumlah_karung_distribusi');
        $totalTerima = $aktivitas->count();

        return view('guest.pondok', compact('pondok', 'aktivitas', 'totalKg', 'totalKarung', 'totalTerima'));
    }
}