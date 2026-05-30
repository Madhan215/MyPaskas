<?php

namespace App\Http\Controllers;

use App\Models\{Stok, Distribution};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    public function index()
    {
        $stoks = Stok::with('user')->orderByDesc('tanggal')->get();
        $totalMasuk = Stok::sum('jumlah_karung');
        $totalKeluar = Distribution::sum('jumlah_karung_distribusi');
        $totalStok = max(0, $totalMasuk - $totalKeluar);
        $totalStokKg = max(0, Stok::sum('berat_kg') - Distribution::sum('jumlah_kg_distribusi'));
        return view('stok.index', compact('stoks', 'totalMasuk', 'totalKeluar', 'totalStok', 'totalStokKg'));
    }

    public function create()
    {
        return view('stok.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'jumlah_karung' => 'required|integer|min:1',
            'sumber'        => 'required|string',
        ], [
            'tanggal.required'       => 'Tanggal wajib diisi',
            'jumlah_karung.required' => 'Jumlah karung wajib diisi',
            'sumber.required'        => 'Sumber/donatur wajib diisi',
        ]);

        Stok::create([
            'tanggal'       => $request->tanggal,
            'jumlah_karung' => $request->jumlah_karung,
            'berat_kg'      => $request->jumlah_karung * 10,
            'sumber'        => $request->sumber,
            'keterangan'    => $request->keterangan,
            'user_id'       => Auth::id(),
        ]);

        return redirect()->route('stok.index')->with('success', 'Data loading beras berhasil disimpan');
    }

    public function destroy(Stok $stok)
    {
        $stok->delete();
        return redirect()->route('stok.index')->with('success', 'Data berhasil dihapus');
    }
}