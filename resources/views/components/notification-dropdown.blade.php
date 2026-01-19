<div class="p-4">
    <h3 class="font-semibold mb-3">Notifikasi</h3>

    @forelse($notifications as $notif)
        <a href="{{ $notif['url'] }}"
           class="block p-3 mb-2 rounded
                  {{ $notif['is_read'] ? 'bg-gray-100' : 'bg-blue-50' }}">
            <p class="font-medium">{{ $notif['title'] }}</p>
            <p class="text-sm text-gray-600">{{ $notif['message'] }}</p>
        </a>
    @empty
        <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
    @endforelse
</div>
