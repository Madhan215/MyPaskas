<?php

namespace App\Http\Controllers;

use App\Models\{Distribution, Plan};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoundationDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pondok = $user->pondok;

        if (!$pondok) {
            return redirect()->route('dashboard')->with('error', 'Akun ini belum terhubung ke data pondok. Hubungi admin.');
        }

        // Total diterima
        $totalKg = Distribution::where('pondok_id', $pondok->id)->sum('jumlah_kg_realisasi');
        $totalKarung = Distribution::where('pondok_id', $pondok->id)->sum('jumlah_karung_realisasi');
        $totalTerima = Distribution::where('pondok_id', $pondok->id)->count();

        // Riwayat per seri
        $riwayat = Distribution::with('seri')
            ->where('pondok_id', $pondok->id)
            ->orderByDesc('tanggal_realisasi')
            ->get();

        // Jadwal mendatang
        $jadwalMendatang = Plan::with('seri')
            ->where('pondok_id', $pondok->id)
            ->where('status', 'belum')
            ->orderBy('tanggal_rencana')
            ->get();

        // Grafik per bulan
        $grafikBulanan = Distribution::where('pondok_id', $pondok->id)
            ->select(
                DB::raw('YEAR(tanggal_realisasi) as tahun'),
                DB::raw('MONTH(tanggal_realisasi) as bulan'),
                DB::raw('SUM(jumlah_kg_realisasi) as total_kg')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')->orderBy('bulan')
            ->get();

        return view('pondok.dashboard', compact(
            'pondok',
            'totalKg',
            'totalKarung',
            'totalTerima',
            'riwayat',
            'jadwalMendatang',
            'grafikBulanan'
        ));
    }
}