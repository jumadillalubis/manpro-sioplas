<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Halaman utama Settings
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Halaman ubah password
     */
    public function password()
    {
        return view('settings.password');
    }

    /**
     * Proses update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!$user) {
            return back()->withErrors(['user' => 'User tidak ditemukan']);
        }

        if (!\Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah']);
        }

        $user->password = \Hash::make($request->password_baru);
        $user->save();

        return redirect()->route('settings')
            ->with('success', 'Password berhasil diubah');
    }
}
