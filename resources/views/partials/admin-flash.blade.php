@if(session('success') || session('warning') || session('info') || session('error'))
    @foreach(['success' => 'check-circle', 'warning' => 'alert', 'info' => 'info', 'error' => 'alert-circle'] as $type => $icon)
        @if(session($type))
            <div class="admin-flash admin-flash-{{ $type }}" role="status" data-flash>
                <x-icon :name="$icon" :size="19" />
                <p>{{ session($type) }}</p>
                <button type="button" class="flash-close" data-flash-close aria-label="Tutup notifikasi">
                    <x-icon name="close" :size="15" />
                </button>
            </div>
        @endif
    @endforeach
@endif
