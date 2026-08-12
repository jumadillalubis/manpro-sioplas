@php
    use App\Http\Controllers\NotificationController;

    $notifications = NotificationController::getNotifications()->where('is_read', false);
    $unreadCount   = NotificationController::unreadCount();
@endphp

<div class="topbar" style="display:flex;justify-content:flex-end;gap:24px;align-items:center;position:relative;">

    {{-- 🔔 NOTIFIKASI --}}
    <div style="position:relative;display:inline-block;">

        {{-- Tombol Bell --}}
        <button id="notifBtn" style="
            background:none;
            border:none;
            cursor:pointer;
            font-size:20px;
            position:relative;
            padding:4px;
            line-height:1;
        ">
            🔔
            {{-- Badge angka --}}
            <span id="notif-badge" style="
                position:absolute;
                top:-4px;
                right:-6px;
                background:#e74c3c;
                color:white;
                font-size:10px;
                font-weight:700;
                min-width:18px;
                height:18px;
                padding:0 5px;
                border-radius:999px;
                display:{{ $unreadCount > 0 ? 'flex' : 'none' }};
                align-items:center;
                justify-content:center;
                line-height:1;
                transition:transform 0.25s ease;
                font-family:sans-serif;
            ">{{ $unreadCount }}</span>
        </button>

        {{-- Dropdown Panel --}}
        <div id="notifDropdown" style="
            display:none;
            position:absolute;
            top:calc(100% + 10px);
            right:0;
            width:320px;
            background:#ffffff;
            border:1px solid #e5e7eb;
            border-radius:12px;
            box-shadow:0 10px 30px rgba(0,0,0,0.12);
            z-index:9999;
            overflow:hidden;
        ">
            {{-- Header Dropdown --}}
            <div style="
                padding:12px 16px;
                font-weight:700;
                font-size:14px;
                border-bottom:1px solid #f0f0f0;
                display:flex;
                align-items:center;
                justify-content:space-between;
                background:#fafafa;
            ">
                <span>🔔 Notifikasi</span>
                <span id="notif-header-count" style="font-size:11px;font-weight:500;color:#9ca3af;">
                    {{ $unreadCount > 0 ? $unreadCount . ' belum dibaca' : 'Semua dibaca' }}
                </span>
            </div>

            {{-- List Notifikasi (scrollable) --}}
            <div id="notif-list" style="max-height:320px;overflow-y:auto;">
                @forelse($notifications as $notif)
                    <a href="{{ route('notification.read', ['id' => $notif['id'], 'redirect' => $notif['url']]) }}"
                       style="
                           display:block;
                           padding:12px 16px;
                           text-decoration:none;
                           color:#1f2937;
                           background:#eff6ff;
                           border-bottom:1px solid #f3f4f6;
                           transition:background 0.15s;
                       "
                       onmouseover="this.style.background='#dbeafe'"
                       onmouseout="this.style.background='#eff6ff'"
                    >
                        <div style="font-weight:600;font-size:13px;margin-bottom:2px;">
                            {{ $notif['title'] }}
                        </div>
                        <div style="font-size:12px;color:#6b7280;line-height:1.45;">
                            {{ $notif['message'] }}
                        </div>
                    </a>
                @empty
                    <div style="padding:24px 16px;font-size:13px;color:#9ca3af;text-align:center;">
                        ✅ Tidak ada notifikasi baru
                    </div>
                @endforelse
            </div>

            {{-- Footer: status polling --}}
            <div style="
                padding:8px 16px;
                font-size:11px;
                color:#d1d5db;
                text-align:right;
                border-top:1px solid #f3f4f6;
                background:#fafafa;
            ">
                <span id="notif-last-updated">Diperbarui saat halaman dimuat</span>
                <span id="notif-loading" style="display:none;color:#6ec8e0;">⏳ Memperbarui...</span>
            </div>
        </div>
    </div>

    {{-- 👤 PROFILE --}}
    @php
        // Inisial nama (maks 2 huruf)
        $_nama    = session('user_nama', 'U');
        $_words   = explode(' ', trim($_nama));
        $_inisial = strtoupper(substr($_words[0], 0, 1));
        if (count($_words) > 1) $_inisial .= strtoupper(substr($_words[1], 0, 1));

        // Warna avatar sesuai jabatan (sama dengan Settings page)
        $_jabatanLow  = strtolower(session('user_jabatan', ''));
        $_avatarColor = '#6ec8e0'; // default biru (Staff)
        if (str_contains($_jabatanLow, 'atasan') || str_contains($_jabatanLow, 'kepala dinas')) {
            $_avatarColor = '#8b5cf6'; // ungu
        } elseif (str_contains($_jabatanLow, 'katimja') || str_contains($_jabatanLow, 'kepala tim') || str_contains($_jabatanLow, 'sekretaris')) {
            $_avatarColor = '#f59e0b'; // amber
        }
    @endphp
    <div class="profile" style="display:flex;align-items:center;gap:10px;">
        {{-- Avatar Inisial --}}
        <div style="
            width:36px;
            height:36px;
            border-radius:50%;
            background:{{ $_avatarColor }};
            color:white;
            font-size:13px;
            font-weight:800;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
            letter-spacing:0.5px;
            box-shadow:0 2px 8px rgba(0,0,0,0.15);
        ">{{ $_inisial }}</div>
        <div>
            <h4 style="margin:0;font-size:14px;font-weight:600;">
                {{ session('user_nama','User') }}
            </h4>
            <p style="margin:0;font-size:12px;color:var(--muted);">
                {{ session('user_jabatan','-') }}
            </p>
        </div>
    </div>

</div>

<script>
// ─── Toggle Dropdown ──────────────────────────────────────────────────────────
const notifBtn      = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');

notifBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    const isOpen = notifDropdown.style.display === 'block';
    notifDropdown.style.display = isOpen ? 'none' : 'block';
});

document.addEventListener('click', function (e) {
    if (!notifDropdown.contains(e.target) && e.target !== notifBtn) {
        notifDropdown.style.display = 'none';
    }
});

// Tutup dropdown saat tekan Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') notifDropdown.style.display = 'none';
});

// ─── Polling Otomatis (setiap 30 detik) ──────────────────────────────────────
const POLL_MS          = 30000;
const notifBadge       = document.getElementById('notif-badge');
const notifList        = document.getElementById('notif-list');
const notifHeaderCount = document.getElementById('notif-header-count');
const notifLastUpdated = document.getElementById('notif-last-updated');
const notifLoading     = document.getElementById('notif-loading');
const pollUrl          = "{{ route('notification.live') }}";

function escapeHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str || ''));
    return d.innerHTML;
}

function formatTime(date) {
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

function animateBadge() {
    notifBadge.style.transform = 'scale(1.5)';
    setTimeout(() => { notifBadge.style.transform = 'scale(1)'; }, 300);
}

function renderNotifications(count, notifications) {
    // Update badge
    const prevCount = parseInt(notifBadge.textContent) || 0;
    if (count > 0) {
        notifBadge.textContent   = count;
        notifBadge.style.display = 'flex';
        if (count > prevCount) animateBadge();
    } else {
        notifBadge.style.display = 'none';
    }

    // Update teks header
    notifHeaderCount.textContent = count > 0
        ? count + ' belum dibaca'
        : 'Semua dibaca';

    // Update list
    if (!notifications || notifications.length === 0) {
        notifList.innerHTML = `
            <div style="padding:24px 16px;font-size:13px;color:#9ca3af;text-align:center;">
                ✅ Tidak ada notifikasi baru
            </div>`;
        return;
    }

    const baseUrl = "{{ url('/notification/read') }}";
    notifList.innerHTML = notifications.map(n => `
        <a href="${baseUrl}/${n.id}?redirect=${encodeURIComponent(n.url)}"
           style="display:block;padding:12px 16px;text-decoration:none;color:#1f2937;
                  background:#eff6ff;border-bottom:1px solid #f3f4f6;transition:background 0.15s;"
           onmouseover="this.style.background='#dbeafe'"
           onmouseout="this.style.background='#eff6ff'"
        >
            <div style="font-weight:600;font-size:13px;margin-bottom:2px;">${escapeHtml(n.title)}</div>
            <div style="font-size:12px;color:#6b7280;line-height:1.45;">${escapeHtml(n.message)}</div>
        </a>
    `).join('');
}

async function pollNotifications() {
    notifLoading.style.display     = 'inline';
    notifLastUpdated.style.display = 'none';

    try {
        const res = await fetch(pollUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });

        if (res.status === 401) {
            clearInterval(pollingTimer);
            return;
        }

        if (res.ok) {
            const data = await res.json();
            renderNotifications(data.count || 0, data.notifications || []);
        }
    } catch (err) {
        console.warn('[SIOPLAS Notif] Polling gagal:', err.message);
    } finally {
        notifLoading.style.display     = 'none';
        notifLastUpdated.style.display = 'inline';
        notifLastUpdated.textContent   = 'Diperbarui ' + formatTime(new Date());
    }
}

// Polling pertama setelah 5 detik, lalu setiap 30 detik
setTimeout(pollNotifications, 5000);
const pollingTimer = setInterval(pollNotifications, POLL_MS);
</script>
