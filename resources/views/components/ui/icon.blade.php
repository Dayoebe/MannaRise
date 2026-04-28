@props(['name', 'class' => 'h-4 w-4'])

<svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '2', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('book-open')
            <path d="M12 7v14" />
            <path d="M3 5.5A2.5 2.5 0 0 1 5.5 3H12v18H5.5A2.5 2.5 0 0 1 3 18.5z" />
            <path d="M21 5.5A2.5 2.5 0 0 0 18.5 3H12v18h6.5A2.5 2.5 0 0 0 21 18.5z" />
            @break
        @case('library')
            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
            <path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z" />
            <path d="M8 6h8" />
            <path d="M8 10h6" />
            @break
        @case('heart')
            <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 1 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" />
            @break
        @case('sparkles')
            <path d="m12 3 1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6z" />
            <path d="m19 15 .8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z" />
            <path d="m5 14 .8 2.2L8 17l-2.2.8L5 20l-.8-2.2L2 17l2.2-.8z" />
            @break
        @case('message-circle')
            <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 17 0z" />
            @break
        @case('send')
            <path d="m22 2-7 20-4-9-9-4z" />
            <path d="M22 2 11 13" />
            @break
        @case('search')
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
            @break
        @case('layout-dashboard')
            <rect x="3" y="3" width="7" height="9" rx="1" />
            <rect x="14" y="3" width="7" height="5" rx="1" />
            <rect x="14" y="12" width="7" height="9" rx="1" />
            <rect x="3" y="16" width="7" height="5" rx="1" />
            @break
        @case('journal')
            <path d="M4 19.5V4a2 2 0 0 1 2-2h12v20H6a2 2 0 0 1-2-2.5z" />
            <path d="M8 6h6" />
            <path d="M8 10h8" />
            <path d="M8 14h5" />
            @break
        @case('bookmark')
            <path d="M19 21 12 17 5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" />
            @break
        @case('shield')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            @break
        @case('log-in')
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
            <path d="m10 17 5-5-5-5" />
            <path d="M15 12H3" />
            @break
        @case('log-out')
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <path d="m16 17 5-5-5-5" />
            <path d="M21 12H9" />
            @break
        @case('chevron-left')
            <path d="m15 18-6-6 6-6" />
            @break
        @case('chevron-right')
            <path d="m9 18 6-6-6-6" />
            @break
        @case('star')
            <path d="m12 2 3 6 6.5.9-4.7 4.6 1.1 6.5L12 17l-5.9 3 1.1-6.5L2.5 8.9 9 8z" />
            @break
        @case('bar-chart')
            <path d="M3 3v18h18" />
            <path d="M7 16v-5" />
            <path d="M12 16V7" />
            <path d="M17 16v-8" />
            @break
        @case('settings')
            <path d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5z" />
            <path d="m19.4 15 .2 2.2-2 1.2-1.8-1a7.7 7.7 0 0 1-1.7.7L13.5 20h-3l-.6-1.9a7.7 7.7 0 0 1-1.7-.7l-1.8 1-2-1.2.2-2.2a7 7 0 0 1-.9-1.5L2 12l1.7-1.5c.2-.5.5-1 .9-1.5l-.2-2.2 2-1.2 1.8 1c.5-.3 1.1-.5 1.7-.7L10.5 4h3l.6 1.9c.6.2 1.2.4 1.7.7l1.8-1 2 1.2-.2 2.2c.4.5.7 1 .9 1.5L22 12l-1.7 1.5c-.2.5-.5 1-.9 1.5z" />
            @break
        @case('users')
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.9" />
            <path d="M16 3.1a4 4 0 0 1 0 7.8" />
            @break
        @case('check-circle')
            <circle cx="12" cy="12" r="9" />
            <path d="m9 12 2 2 4-5" />
            @break
        @case('database')
            <ellipse cx="12" cy="5" rx="8" ry="3" />
            <path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5" />
            <path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6" />
            @break
        @case('headphones')
            <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z" />
            <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" />
            @break
        @case('more-horizontal')
            <circle cx="5" cy="12" r="1" />
            <circle cx="12" cy="12" r="1" />
            <circle cx="19" cy="12" r="1" />
            @break
        @default
            <circle cx="12" cy="12" r="9" />
            <path d="M12 8v4l3 3" />
    @endswitch
</svg>
