@props(['compact' => false])

<span {{ $attributes->merge(['class' => 'brand']) }}>
    <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 48 48" fill="none">
            <path d="M24 3 42 10v13c0 11-8 19-18 22C14 42 6 34 6 23V10L24 3Z" fill="currentColor" opacity=".14"/>
            <path d="M24 7 38 12.5V23c0 8.5-6.2 15-14 17.7C16.2 38 10 31.5 10 23V12.5L24 7Z" stroke="currentColor" stroke-width="2"/>
            <path d="M24 16v12M18 22l6-6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M17 31h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
        </svg>
    </span>
    @unless($compact)
        <span class="brand-copy">
            <strong>{{ config('kelurahan.name') }}</strong>
            <small>{{ config('kelurahan.kota') }} · {{ config('kelurahan.provinsi') }}</small>
        </span>
    @endunless
</span>
