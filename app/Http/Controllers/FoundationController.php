<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Foundation;
use App\Models\Plan;
use Illuminate\Http\Request;

class FoundationController extends Controller
{
    public function index()
    {
        $q = request('q');

        $pondoks = Foundation::when($q, function ($query) use ($q) {

            $query->where('nama', 'like', '%' . $q . '%')
                ->orWhere('alamat', 'like', '%' . $q . '%')
                ->orWhere('penanggung_jawab', 'like', '%' . $q . '%');

        })
            ->orderBy('penanggung_jawab')
            ->orderBy('nama')
            ->get();

        return view('foundation.index', compact('pondoks'));
    }

    public function create()
    {
        return view('foundation.form', ['pondok' => null]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'nama'            => 'required|string|max:255',
            'alamat'          => 'required|string',
            'google_maps_url' => 'required|string',
            'jumlah_santri'   => 'required|integer|min:0',
        ], [
            'nama.required'            => 'Nama pondok wajib diisi',
            'alamat.required'          => 'Alamat wajib diisi',
            'google_maps_url.required' => 'Link Maps wajib diisi',
            'jumlah_santri.required'   => 'Jumlah santri wajib diisi',
        ]);

        Foundation::create($request->all());
        return redirect()->route('pondok.index')->with('success', 'Data pondok berhasil disimpan');
    }

    public function edit(Foundation $pondok)
    {
        return view('foundation.form', compact('pondok'));
    }

    public function update(Request $request, Foundation $pondok)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'alamat'        => 'required|string',
            'jumlah_santri' => 'required|integer|min:0',
        ]);

        $pondok->update($request->all());
        return redirect()->route('pondok.index')->with('success', 'Data pondok berhasil diperbarui');
    }

    public function destroy(Foundation $pondok)
    {
        // Hapus data relasi dulu
        Distribution::where('pondok_id', $pondok->id)->delete();

        Plan::where('pondok_id', $pondok->id)->delete();

        // Baru hapus pondok
        $pondok->delete();

        return redirect()
            ->route('pondok.index')
            ->with('success', 'Data pondok berhasil dihapus');
    }
}