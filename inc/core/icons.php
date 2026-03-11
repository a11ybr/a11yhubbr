<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_get_icon_svg($icon) {
    $icons = array(
        'menu' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5" stroke-linecap="round"/></svg>',
        'close' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>',
        'submit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 17V5M7 10l5-5 5 5M5 19h14" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'file-lines' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M8 3h6l5 5v13H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M14 3v5h5M10 13h6M10 17h6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'book-open' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 6.5A2.5 2.5 0 0 1 5.5 4H11v16H5.5A2.5 2.5 0 0 0 3 22zM21 6.5A2.5 2.5 0 0 0 18.5 4H13v16h5.5A2.5 2.5 0 0 1 21 22z" stroke-linejoin="round"/></svg>',
        'wrench' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m14 7 3-3 3 3-3 3M13 8 5 16l-1 4 4-1 8-8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'headphones' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 12a8 8 0 0 1 16 0"/><path d="M6 12h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2Zm10 0h2a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2Z" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'desktop' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4" stroke-linecap="round"/></svg>',
        'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M9.5 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm8 10v-2a4 4 0 0 0-3-3.87M15 4.13a3.5 3.5 0 0 1 0 6.74" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'briefcase' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M3 12h18" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'building' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 21V7l8-4 8 4v14M9 21v-4h6v4M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M16 14h.01" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'hand' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M8 11V5a1 1 0 1 1 2 0v5M10 10V4a1 1 0 1 1 2 0v6M12 10V5a1 1 0 1 1 2 0v5M14 11V7a1 1 0 1 1 2 0v8a5 5 0 0 1-5 5h-1a6 6 0 0 1-6-6v-3a1 1 0 1 1 2 0v2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'eye' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="3"/></svg>',
        'braille' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="8" cy="6" r="1.8"/><circle cx="8" cy="12" r="1.8"/><circle cx="8" cy="18" r="1.8"/><circle cx="16" cy="8" r="1.8"/><circle cx="16" cy="14" r="1.8"/><circle cx="16" cy="20" r="1.8"/></svg>',
        'location' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s6-5.33 6-11a6 6 0 1 0-12 0c0 5.67 6 11 6 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>',
        'bookmark' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 4h10v16l-5-3-5 3V4Z" stroke-linejoin="round"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18" stroke-linecap="round"/></svg>',
        'circle-dot' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="2.5" fill="currentColor" stroke="none"/></svg>',
        'heart' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-7-4.35-9.5-8A5.7 5.7 0 0 1 12 5.5 5.7 5.7 0 0 1 21.5 13c-2.5 3.65-9.5 8-9.5 8Z"/></svg>',
        'github' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.18-3.37-1.18-.46-1.16-1.1-1.47-1.1-1.47-.9-.62.07-.6.07-.6 1 .07 1.52 1.03 1.52 1.03.88 1.5 2.32 1.07 2.88.82.09-.64.35-1.08.63-1.33-2.22-.25-4.56-1.1-4.56-4.9 0-1.08.39-1.97 1.03-2.67-.1-.26-.45-1.28.1-2.67 0 0 .84-.27 2.75 1.02a9.6 9.6 0 0 1 5 0c1.9-1.3 2.74-1.02 2.74-1.02.56 1.39.21 2.41.11 2.67.64.7 1.02 1.59 1.02 2.67 0 3.81-2.35 4.64-4.58 4.88.36.31.68.91.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z"/></svg>',
        'bluesky' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.3 4.8c2 1.5 4.2 4.6 5.7 7.8 1.5-3.2 3.7-6.3 5.7-7.8 1.4-1 3.6-1.8 3.6.9 0 .5-.3 4.1-.5 4.7-.7 2.2-3.2 2.7-5.4 2.3 3.8.7 4.8 3.1 2.7 5.5-4 4.5-5.7-1.1-6.1-2.6-.1-.2-.1-.3-.1-.2 0-.1 0 0-.1.2-.4 1.5-2.1 7.1-6.1 2.6-2.1-2.4-1.1-4.8 2.7-5.5-2.2.4-4.7-.1-5.4-2.3-.2-.6-.5-4.2-.5-4.7 0-2.7 2.2-1.9 3.6-.9Z"/></svg>',
        'x-twitter' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 3H21l-4.6 5.3L22 21h-4.8l-3.8-5-4.3 5H7l5-5.8L2 3h4.9l3.4 4.5L14.2 3h4.7Z"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.94 8.5H3.56V20h3.38V8.5ZM5.25 3A1.97 1.97 0 1 0 5.3 7a1.97 1.97 0 0 0-.05-4ZM20.44 12.72c0-3.4-1.81-4.98-4.22-4.98-1.94 0-2.8 1.07-3.28 1.82V8.5H9.56V20h3.38v-5.7c0-1.5.28-2.95 2.14-2.95 1.83 0 1.85 1.72 1.85 3.05V20h3.38l.13-7.28Z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="4"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
        'folder-open' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 19V7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v1"/><path d="M3 19l3-7h15l-3 7H3Z" stroke-linejoin="round"/></svg>',
    );

    return isset($icons[$icon]) ? $icons[$icon] : '';
}

function a11yhubbr_render_icon($icon, $class = '') {
    $svg = a11yhubbr_get_icon_svg($icon);
    if ($svg === '') {
        return '';
    }

    $classes = trim('a11yhubbr-icon a11yhubbr-icon-' . $icon . ' ' . $class);
    return '<span class="' . esc_attr($classes) . '" aria-hidden="true">' . $svg . '</span>';
}
