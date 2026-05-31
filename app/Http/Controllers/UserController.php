<?php

namespace App\Http\Controllers;

use App\Models\{User, Foundation};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // Only admin can access
    }

    public function index()
    {
        $users = User::orderBy('role')
            ->orderBy('name')
            ->get();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $pondoks = Foundation::where('is_active', true)->orderBy('nama')->get();
        return view('user.form', ['user' => null, 'pondoks' => $pondoks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'role'      => 'required|in:admin,ota,paskas,pondok',
            'password'  => 'required|min:6|confirmed',
            'phone'     => 'nullable|string|max:20',
            'pondok_id' => 'nullable|exists:pondoks,id',
        ], [
            'email.unique'       => 'Email sudah digunakan',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min'       => 'Password minimal 6 karakter',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'pondok_id' => $request->role === 'pondok' ? $request->pondok_id : null,
            'is_active' => true,
        ]);

        return redirect()->route('user.index')->with('success', 'Akun pengguna berhasil dibuat');
    }

    public function edit(User $user)
    {
        $pondoks = Foundation::where('is_active', true)->orderBy('nama')->get();
        return view('user.form', compact('user', 'pondoks'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role'      => 'required|in:admin,ota,paskas,pondok',
            'phone'     => 'nullable|string|max:20',
            'pondok_id' => 'nullable|exists:pondoks,id',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'phone'     => $request->phone,
            'pondok_id' => $request->role === 'pondok' ? $request->pondok_id : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min'       => 'Password minimal 6 karakter',
        ]);

        $user->update(['password' => Hash::make($request->password)]);
        return redirect()->route('user.index')->with('success', "Password {$user->name} berhasil direset");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Akun berhasil dihapus');
    }
}