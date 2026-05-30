<?php

namespace App\Http\Controllers;

use App\Models\{Series, Distribution, Stok};
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $seris = Series::orderByDesc('tahun')->orderByDesc('bulan')->get();
        return view('laporan.index', compact('seris'));
    }

    public function exportPdf(Series $seri)
    {
        $pdfService = new PdfService();
        $html = $pdfService->generateSeriReport($seri);

        // Return as HTML page that users can print-to-PDF
        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function grafikBulanan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Data per bulan
        $distribusiBulanan = Distribution::select(
            DB::raw('MONTH(tanggal_distribusi) as bulan'),
            DB::raw('SUM(jumlah_karung_distribusi) as total_karung'),
            DB::raw('SUM(jumlah_kg_distribusi) as total_kg'),
            DB::raw('COUNT(*) as jumlah_aktivitas')
        )
            ->whereYear('tanggal_distribusi', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        $stokBulanan = Stok::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('SUM(jumlah_karung) as total_karung_masuk'),
            DB::raw('SUM(berat_kg) as total_kg_masuk')
        )
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Siapkan array 12 bulan
        $labels = [];
        $dataDistribusi = [];
        $dataStok = [];
        $dataMasuk = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = \Carbon\Carbon::create(null, $m)->isoFormat('MMM');
            $dataDistribusi[] = $distribusiBulanan->get($m)?->total_karung ?? 0;
            $dataMasuk[] = $stokBulanan->get($m)?->total_karung_masuk ?? 0;
        }

        // Statistik per pondok di tahun ini
        $topPondok = Distribution::with('pondok')
            ->select('pondok_id', DB::raw('SUM(jumlah_kg_distribusi) as total_kg'), DB::raw('COUNT(*) as kali'))
            ->whereYear('tanggal_distribusi', $tahun)
            ->groupBy('pondok_id')
            ->orderByDesc('total_kg')
            ->limit(10)
            ->get();

        // Tahun tersedia
        $tahunList = Distribution::selectRaw('YEAR(tanggal_distribusi) as tahun')
            ->distinct()->orderByDesc('tahun')->pluck('tahun');

        if ($tahunList->isEmpty())
            $tahunList = collect([date('Y')]);

        return view('laporan.grafik', compact(
            'tahun',
            'tahunList',
            'labels',
            'dataDistribusi',
            'dataMasuk',
            'topPondok'
        ));
    }
}