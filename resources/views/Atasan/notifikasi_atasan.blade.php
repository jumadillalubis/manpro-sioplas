@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<style>
  body {
    font-family: 'Poppins', sans-serif;
    overflow: hidden; /* cegah scroll body */
  }

  /* agar hanya isi yang di-scroll */
  .content {
    flex: 1;
    overflow-y: auto;
    height: calc(100vh - 80px); /* sisakan area untuk navbar */
    padding: 30px;
  }

  h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 24px;
  }

  .notif-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 14px 18px;
    background: #f9fafb;
    border-radius: 10px;
    transition: background 0.2s ease;
    border: 1px solid #e5e7eb;
  }
  .notif-item:hover {
    background: #f3f4f6;
  }
  .notif-item .text {
    flex: 1;
  }
  .notif-item .text p {
    margin: 0;
  }
  .notif-item .text .title {
    font-weight: 600;
    color: #111827;
  }
  .notif-item .text .desc {
    color: #6b7280;
    font-size: 14px;
    margin-top: 2px;
  }
  .notif-item .time {
    color: #9ca3af;
    font-size: 14px;
    white-space: nowrap;
    margin-left: 10px;
  }

  /* tombol hapus */
  .delete-btn {
    background: #e5e7eb;
    border: none;
    border-radius: 8px;
    padding: 10px 18px;
    font-weight: 600;
    font-size: 14px;
    margin-top: 28px;
    transition: 0.2s;
    cursor: pointer;
  }
  .delete-btn:hover {
    background: #d1d5db;
  }
</style>

<div class="container px-4 py-3">
  <h2>Notifikasi</h2>

  <div class="notif-list d-flex flex-column gap-3">
    <div class="notif-item">
      <div class="text">
        <p class="title">Q2 Report Deadline</p>
        <p class="desc">Your request for vacation from July 10 to July 15 has been approved.</p>
      </div>
      <div class="time">2d ago</div>
    </div>

    <div class="notif-item">
      <div class="text">
        <p class="title">Q3 Report Deadline</p>
        <p class="desc">The deadline for the Q3 report has been extended to August 31.</p>
      </div>
      <div class="time">3d ago</div>
    </div>

    <div class="notif-item">
      <div class="text">
        <p class="title">Expense Report for Review</p>
        <p class="desc">Please review and approve the expense report submitted by Sarah.</p>
      </div>
      <div class="time">4d ago</div>
    </div>

    <div class="notif-item">
      <div class="text">
        <p class="title">New Feature Request</p>
        <p class="desc">A new feature request has been submitted by the product team.</p>
      </div>
      <div class="time">5d ago</div>
    </div>

    <div class="notif-item">
      <div class="text">
        <p class="title">Performance Review</p>
        <p class="desc">Your performance review is scheduled for next week.</p>
      </div>
      <div class="time">6d ago</div>
    </div>

    <div class="notif-item">
      <div class="text">
        <p class="title">Company Meeting</p>
        <p class="desc">The company-wide meeting will be held on Friday at 2 PM.</p>
      </div>
      <div class="time">7d ago</div>
    </div>
  </div>

  <div class="text-center">
    <button class="delete-btn" id="deleteAllBtn">Hapus Semua Notifikasi</button>
  </div>
</div>

<script>
  document.getElementById('deleteAllBtn').addEventListener('click', function() {
    const notifList = document.querySelector('.notif-list');
    notifList.innerHTML = '<p style="text-align:center; color:#6b7280;">Tidak ada notifikasi.</p>';
  });
</script>
@endsection
