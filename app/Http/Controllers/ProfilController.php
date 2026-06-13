<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profil.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,heif,heic',
        ], [
            'foto_profil.image' => 'File harus berupa gambar',
        ]);

        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {

            // Hapus foto lama
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $file = $request->file('foto_profil');

            $filename = 'profil_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // simpan ke storage/app/public/profil
            $path = $file->storeAs('profil', $filename, 'public');

            // simpan path ke database
            $data['foto_profil'] = $path;
        }

        $user->update($data);

        return redirect()->route('profil.show')->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok',
            'password.min'       => 'Password baru minimal 6 karakter',
        ]);

        if (!Hash::check($request->password_lama, Auth::user()->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return redirect()->route('profil.show')->with('success', 'Password berhasil diubah');
    }




}