<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // ganti sesuai model login kamu
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|integer|exists:users,id', 
            'new_password' => 'required|string|min:6|confirmed', 
        ]);

        // Cari user
        $user = User::find($request->user_id);

        // Update password
        $user->password = Hash::make($request->new_password);

        // Optional: simpan waktu reset / logout
        $user->last_logout = Carbon::now();

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password berhasil diubah'
        ]);
    }
}
