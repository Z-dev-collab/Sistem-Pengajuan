@if(session('success') || session('warning') || session('info') || session('error'))
    <div class="container flash-wrap">
        @foreach(['success' => 'check-circle', 'warning' => 'alert', 'info' => 'info', 'error' => 'alert-circle'] as $type => $icon)
            @if(session($type))
                <div class="flash flash-{{ $type }}" role="status" data-flash>
                    <x-icon :name="$icon" :size="20" />
                    <p>{{ session($type) }}</p>
                    <button type="button" class="flash-close" data-flash-close aria-label="Tutup notifikasi">
                        <x-icon name="close" :size="16" />
                    </button>
                </div>
            @endif
        @endforeach
    </div>
@endif
