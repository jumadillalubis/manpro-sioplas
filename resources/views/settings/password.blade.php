@extends('layouts.app')

@section('content')
<div class="p-8 max-w-md mx-auto">

    <h1 class="text-xl font-semibold mb-6">Ubah Password</h1>

    <form method="POST" action="{{ route('settings.password.update') }}"
          class="bg-white p-6 rounded shadow space-y-4">
        @csrf

        <div>
            <label class="block text-sm mb-1">Password Lama</label>
            <input type="password" name="password_lama"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm mb-1">Password Baru</label>
            <input type="password" name="password_baru"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_baru_confirmation"
                   class="w-full border rounded px-3 py-2">
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded">
            Simpan Password
        </button>
    </form>

</div>
@endsection
