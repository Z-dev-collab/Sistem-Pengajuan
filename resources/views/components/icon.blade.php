@props(['name' => 'file-text', 'size' => 24])

<svg
    {{ $attributes->merge(['class' => 'icon', 'width' => $size, 'height' => $size]) }}
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="1.8"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
>
    @switch($name)
        @case('search')
            <circle cx="11" cy="11" r="7"></circle>
            <path d="m20 20-3.5-3.5"></path>
            @break
        @case('arrow-right')
            <path d="M5 12h14"></path>
            <path d="m13 6 6 6-6 6"></path>
            @break
        @case('arrow-left')
            <path d="M19 12H5"></path>
            <path d="m11 18-6-6 6-6"></path>
            @break
        @case('arrow-up-right')
            <path d="M7 17 17 7"></path>
            <path d="M8 7h9v9"></path>
            @break
        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"></path>
            @break
        @case('close')
            <path d="m6 6 12 12M18 6 6 18"></path>
            @break
        @case('phone')
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"></path>
            @break
        @case('map-pin')
            <path d="M20 10c0 5-8 12-8 12s-8-7-8-12a8 8 0 1 1 16 0Z"></path>
            <circle cx="12" cy="10" r="3"></circle>
            @break
        @case('clock')
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M12 7v5l3 2"></path>
            @break
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
            <path d="m3 7 9 6 9-6"></path>
            @break
        @case('whatsapp')
            <path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.4L3 20.5l1.5-4.8A8.5 8.5 0 1 1 20.5 11.6Z"></path>
            <path d="M8.5 7.9c.2-.5.4-.5.8-.5h.6c.2 0 .4.1.5.4l.8 1.9c.1.2 0 .5-.1.7l-.6.7c-.2.2-.2.4 0 .7.5.9 1.3 1.7 2.3 2.2.3.1.5.1.7-.1l.7-.8c.2-.2.4-.2.7-.1l1.8.9c.3.1.4.3.4.5 0 .6-.2 1.3-.6 1.7-.4.4-1 .7-1.7.8-1.6.2-3.9-.8-5.4-2.3-1.5-1.5-2.4-3.4-2.2-4.8.1-.6.2-1.2.3-1.7Z"></path>
            @break
        @case('chevron-down')
            <path d="m6 9 6 6 6-6"></path>
            @break
        @case('chevron-right')
            <path d="m9 18 6-6-6-6"></path>
            @break
        @case('check')
            <path d="m5 12 4 4L19 6"></path>
            @break
        @case('check-circle')
            <circle cx="12" cy="12" r="9"></circle>
            <path d="m8 12 2.5 2.5L16 9"></path>
            @break
        @case('wallet')
            <path d="M20 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h15a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5"></path>
            <path d="M16 12h4M16 16h4"></path>
            @break
        @case('file-text')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
            <path d="M14 2v6h6M8 13h8M8 17h5"></path>
            @break
        @case('file-check')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
            <path d="M14 2v6h6M8 14l2 2 4-4"></path>
            @break
        @case('download')
            <path d="M12 3v12"></path>
            <path d="m7 10 5 5 5-5"></path>
            <path d="M5 21h14"></path>
            @break
        @case('upload')
            <path d="M12 16V4"></path>
            <path d="m7 9 5-5 5 5"></path>
            <path d="M5 20h14"></path>
            @break
        @case('users')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
            @break
        @case('user')
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
            @break
        @case('rings')
            <circle cx="9" cy="14" r="5"></circle>
            <circle cx="15" cy="10" r="5"></circle>
            @break
        @case('shield-check')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path>
            <path d="m9 12 2 2 4-4"></path>
            @break
        @case('alert')
            <path d="M10.3 3.6 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.6a2 2 0 0 0-3.4 0Z"></path>
            <path d="M12 9v4M12 17h.01"></path>
            @break
        @case('alert-circle')
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M12 8v5M12 16h.01"></path>
            @break
        @case('inbox')
            <path d="M22 12h-6l-2 3h-4l-2-3H2"></path>
            <path d="M5.5 5.1 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.5-6.9A2 2 0 0 0 16.8 4H7.2a2 2 0 0 0-1.7 1.1Z"></path>
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
            <path d="M16 3v4M8 3v4M3 11h18"></path>
            @break
        @case('plus')
            <path d="M12 5v14M5 12h14"></path>
            @break
        @case('eye')
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
            <circle cx="12" cy="12" r="3"></circle>
            @break
        @case('edit')
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
            @break
        @case('trash')
            <path d="M3 6h18M8 6V4h8v2M19 6l-1 15H6L5 6M10 11v6M14 11v6"></path>
            @break
        @case('dashboard')
            <rect x="3" y="3" width="7" height="7" rx="1"></rect>
            <rect x="14" y="3" width="7" height="7" rx="1"></rect>
            <rect x="3" y="14" width="7" height="7" rx="1"></rect>
            <rect x="14" y="14" width="7" height="7" rx="1"></rect>
            @break
        @case('logout')
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <path d="m16 17 5-5-5-5M21 12H9"></path>
            @break
        @case('bell')
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"></path>
            @break
        @case('external-link')
            <path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
            @break
        @case('info')
            <circle cx="12" cy="12" r="9"></circle>
            <path d="M12 11v5M12 8h.01"></path>
            @break
        @case('accessibility')
            <circle cx="12" cy="4" r="2"></circle>
            <path d="M5 8h14M12 8v6M8 22l4-8 4 8M8 13l4 1 4-1"></path>
            @break
        @case('lock')
            <rect x="4" y="10" width="16" height="11" rx="2"></rect>
            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
            @break
        @case('filter')
            <path d="M4 5h16l-6 7v6l-4 2v-8Z"></path>
            @break
        @case('send')
            <path d="m22 2-7 20-4-9-9-4Z"></path>
            <path d="M22 2 11 13"></path>
            @break
        @case('copy')
            <rect x="9" y="9" width="12" height="12" rx="2"></rect>
            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            @break
        @case('sparkles')
            <path d="M12 3l1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6Z"></path>
            <path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8Z"></path>
            @break
        @case('heart-handshake')
            <path d="M19 14c1.5-1.5 3-3.3 3-5.5A4.5 4.5 0 0 0 17.5 4c-1.8 0-3 .9-3.5 1.5C13.5 4.9 12.3 4 10.5 4A4.5 4.5 0 0 0 6 8.5c0 2.2 1.5 4 3 5.5l4 4 2-2"></path>
            <path d="m12 15 2-2 2 2M3 13l3 3M21 13l-3 3"></path>
            @break
        @case('lightbulb')
            <path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.3 1 2.3h6c0-1 .4-1.8 1-2.3A7 7 0 0 0 12 2Z"></path>
            @break
        @case('printer')
            <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <rect x="6" y="14" width="12" height="8"></rect>
            @break
        @case('home')
            <path d="m3 10 9-8 9 8v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Z"></path>
            <path d="M9 21v-7h6v7"></path>
            @break
        @case('clipboard')
            <path d="M9 2h6v4H9zM8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2"></path>
            <path d="M8 11h8M8 15h6"></path>
            @break
        @case('message-circle')
            <path d="M21 11.5a8.4 8.4 0 0 1-9 8.5 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.2a8.5 8.5 0 1 1 16.1-4.3Z"></path>
            @break
        @case('circle')
            <circle cx="12" cy="12" r="9"></circle>
            @break
        @case('more-horizontal')
            <circle cx="5" cy="12" r="1"></circle>
            <circle cx="12" cy="12" r="1"></circle>
            <circle cx="19" cy="12" r="1"></circle>
            @break
        @default
            <circle cx="12" cy="12" r="9"></circle>
    @endswitch
</svg>
