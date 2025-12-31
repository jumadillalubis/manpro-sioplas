@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
  <div class="container mt-5">
    <h3>Ganti Password</h3>

    <form action="{{ route('profile.updatePassword') }}" method="POST">
      @csrf
      <div class="form-group">
        <label for="current_password">Kata Sandi Saat Ini</label>
        <input type="password" name="current_password" id="current_password" class="form-control" required>
      </div>
      <div class="form-group mt-3">
        <label for="new_password">Kata Sandi Baru</label>
        <input type="password" name="new_password" id="new_password" class="form-control" required>
      </div>
      <div class="form-group mt-3">
        <label for="new_password_confirmation">Konfirmasi Kata Sandi Baru</label>
        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
      </div>
      <div class="mt-4">
        <button type="submit" class="btn btn-primary">Konfirmasi</button>
      </div>
    </form>
  </div>
@endsection
