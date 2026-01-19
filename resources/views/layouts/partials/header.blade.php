@php
    use App\Http\Controllers\NotificationController;

    $notifications = NotificationController::getNotifications();
    $unreadCount   = NotificationController::unreadCount();
@endphp

<div class="topbar" style="display:flex;justify-content:flex-end;gap:24px;align-items:center;position:relative;">

    {{-- 🔔 NOTIFIKASI --}}
    <div class="notif" style="position:relative;">
        <button id="notifBtn" style="background:none;border:none;cursor:pointer;font-size:18px;">
            🔔
            @if($unreadCount > 0)
                <span style="
                    position:absolute;
                    top:-6px;
                    right:-8px;
                    background:red;
                    color:white;
                    font-size:11px;
                    padding:2px 6px;
                    border-radius:999px;">
                    {{ $unreadCount }}
                </span>
            @endif
        </button>

        {{-- DROPDOWN --}}
        <div id="notifDropdown"
             style="
                display:none;
                position:absolute;
                right:0;
                top:28px;
                width:300px;
                background:#fff;
                border:1px solid #ddd;
                border-radius:8px;
                box-shadow:0 4px 12px rgba(0,0,0,.1);
                z-index:999;
             ">

            <div style="padding:12px;font-weight:600;border-bottom:1px solid #eee;">
                Notifikasi
            </div>

            @forelse($notifications as $notif)
                <a href="{{ $notif['url'] }}"
                   style="
                        display:block;
                        padding:10px 12px;
                        text-decoration:none;
                        color:#333;
                        background:{{ $notif['is_read'] ? '#f9f9f9' : '#eef4ff' }};
                        border-bottom:1px solid #eee;
                   ">
                    <div style="font-weight:600;font-size:13px;">
                        {{ $notif['title'] }}
                    </div>
                    <div style="font-size:12px;color:#666;">
                        {{ $notif['message'] }}
                    </div>
                </a>
            @empty
                <div style="padding:12px;font-size:13px;color:#777;">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>
    </div>

    {{-- 👤 PROFILE --}}
    <div class="profile" style="display:flex;align-items:center;gap:10px;">
        <img src="https://i.pravatar.cc/100?u={{ session('user_id','default') }}"
             style="width:36px;height:36px;border-radius:50%;">

        <div class="profile-info">
            <h4 style="margin:0;font-size:14px;">
                {{ session('user_nama','User') }}
            </h4>
            <p style="margin:0;font-size:12px;color:var(--muted);">
                {{ session('user_jabatan','-') }}
            </p>
        </div>
    </div>

</div>

{{-- SCRIPT --}}
<script>
    document.getElementById('notifBtn').addEventListener('click', function (e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notifDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function () {
        document.getElementById('notifDropdown').style.display = 'none';
    });
</script>
