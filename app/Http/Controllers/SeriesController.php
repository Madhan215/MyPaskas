<?php

namespace App\Http\Controllers;

use App\Models\{Series, Foundation, Plan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeriesController extends Controller
{
    public function index()
    {
        $seris = Series::with('user')->orderByDesc('tahun')->orderByDesc('bulan')->get();
        return view('seri.index', compact('seris'));
    }

    public function create()
    {
        return view('seri.form', ['seri' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $seri = Series::create(array_merge($request->all(), ['user_id' => Auth::id(), 'status' => 'draft']));
        return redirect()->route('seri.jadwal', $seri)->with('success', 'Seri berhasil dibuat! Sekarang buat jadwal distribusinya.');
    }

    public function show(Series $seri)
    {
        $jadwals = $seri->jadwals()->with('pondok')->orderBy('tanggal')->get();
        return view('seri.show', compact('seri', 'jadwals'));
    }

    public function jadwal(Series $seri)
    {
        $pondoks = Foundation::where('is_active', true)->orderBy('penanggung_jawab')->orderBy('nama')->get();
        $jadwals = $seri->jadwals()->with('pondok')->orderBy('tanggal')->get();
        return view('seri.jadwal', compact('seri', 'pondoks', 'jadwals'));
    }

    public function storeJadwal(Request $request, Series $seri)
    {
        $request->validate([
            'pondok_id'     => 'required|exists:foundations,id',
            'tanggal'       => 'required|date',
            'jumlah_karung' => 'required|integer|min:1',
            'petugas'       => 'required|string',
        ]);

        $kg = $request->jumlah_karung * 10;

        Plan::create([
            'seri_id'       => $seri->id,
            'pondok_id'     => $request->pondok_id,
            'tanggal'       => $request->tanggal,
            'jumlah_karung' => $request->jumlah_karung,
            'jumlah_kg'     => $kg,
            'petugas'       => $request->petugas,
            'status'        => 'belum',
            'catatan'       => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Jadwal distribusi berhasil ditambahkan');
    }

    public function aktivasi(Series $seri)
    {
        if ($seri->jadwals()->count() === 0) {
            return back()->with('error', 'Seri harus memiliki minimal 1 jadwal distribusi sebelum diaktifkan');
        }
        // Nonaktifkan seri lain yang aktif
        Series::where('status', 'aktif')->update(['status' => 'draft']);
        $seri->update(['status' => 'aktif']);
        return back()->with('success', 'Seri berhasil diaktifkan');
    }

    public function selesai(Series $seri)
    {
        $seri->update(['status' => 'selesai']);
        return back()->with('success', 'Seri ditandai selesai');
    }
}