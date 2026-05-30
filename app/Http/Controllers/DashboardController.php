<?php

namespace App\Http\Controllers;

use App\Models\{Foundation, Series, Stok, Distribution, Plan};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stokKarung = Stok::totalStokKarung();
        $stokKg = Stok::totalStokKg();
        $totalPondok = Foundation::where('is_active', true)->count();
        $seriAktif = Series::where('status', 'aktif')->first();

        $totalSantri = Foundation::where('is_active', true)->sum('jumlah_santri');
        $jadwalBelum = Plan::where('status', 'belum')->count();
        $jadwalSelesai = Plan::where('status', 'selesai')->count();
        $totalKgDisalurkan = Distribution::sum('jumlah_kg_distribusi');

        // Grafik harian 7 hari terakhir
        $grafikHarian = Distribution::select(
            DB::raw('DATE(tanggal_distribusi) as tanggal'),
            DB::raw('SUM(jumlah_karung_distribusi) as total_karung'),
            DB::raw('SUM(jumlah_kg_distribusi) as total_kg')
        )
            ->where('tanggal_distribusi', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Grafik per kelompok petugas (seri aktif)
        $grafikKelompok = [];
        if ($seriAktif) {
            $grafikKelompok = Plan::where('seri_id', $seriAktif->id)
                ->select('petugas', DB::raw('SUM(jumlah_karung) as rencana'), DB::raw('COUNT(*) as total_titik'))
                ->groupBy('petugas')
                ->get();
        }

        // Aktivitas terbaru
        $aktivitasTerbaru = Distribution::with(['pondok', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // Jadwal mendatang
        $jadwalMendatang = Plan::with('pondok')
            ->where('status', 'belum')
            ->where('tanggal', '>=', now())
            ->orderBy('tanggal')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'stokKarung',
            'stokKg',
            'totalPondok',
            'seriAktif',
            'totalSantri',
            'jadwalBelum',
            'jadwalSelesai',
            'totalKgDisalurkan',
            'grafikHarian',
            'grafikKelompok',
            'aktivitasTerbaru',
            'jadwalMendatang'
        ));
    }
}