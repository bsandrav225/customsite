<?php
/**
 * Единый набор контурных SVG-иконок.
 * Стиль: stroke, скруглённые линии, толщина 1.6.
 */
function icon(string $name, int $size = 24, string $class = ''): string
{
    $classAttr = trim('icon icon--' . preg_replace('/[^a-z0-9-]/', '', $name) . ' ' . $class);
    return '<svg class="' . h($classAttr) . '" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="#i-' . h($name) . '"></use></svg>';
}

function icons_sprite(): void
{
    $icons = [
        'shirt' => '<path d="M8 5.5L4.5 8v3h3v8.5h9V11h3V8L16 5.5s-1 .8-4 .8-4-.8-4-.8z"/><path d="M8 5.5C8 4 9.5 3 12 3s4 1 4 2.5"/>',
        'hoodie' => '<path d="M8 8.5L5 10.5v10h14v-10L16 8.5"/><path d="M8 8.5V7c0-2 1.8-4 4-4s4 2 4 4v1.5"/><path d="M10 21.5v-5h4v5"/><path d="M9.5 8.5c.6.8 1.5 1.2 2.5 1.2s1.9-.4 2.5-1.2"/>',
        'denim' => '<path d="M8 4h8l1 16H7L8 4z"/><path d="M8 4c0 2 1.8 3.5 4 3.5S16 6 16 4"/><path d="M12 7.5v12.5"/><path d="M9 14h2.2M12.8 14H15"/>',
        'jacket' => '<path d="M8 6.5L4.5 9v11.5h15V9L16 6.5"/><path d="M8 6.5C8 4.5 9.7 3 12 3s4 1.5 4 3.5"/><path d="M12 9.5v11"/><path d="M8 6.5l4 3 4-3"/>',
        'shoe' => '<path d="M4 15.5h13.5c2 0 2.5-1.5 2.5-2.5 0-2-2-3-4.2-3.2C14 8 12.2 6.5 9.5 6.5 7 6.5 5.5 8 5 10.5c-1.2.4-2 1.6-2 3 0 1.2.5 2 1 2z"/><path d="M7 12.5h3.5M7.5 10.2h2"/>',
        'bag' => '<path d="M6 9.5h12l-.8 10.5H6.8L6 9.5z"/><path d="M9 9.5V7.5A3 3 0 0 1 12 4.5a3 3 0 0 1 3 3v2"/>',
        'other' => '<circle cx="12" cy="12" r="8"/><path d="M12 8v8M8 12h8"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-left' => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'x' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'upload' => '<path d="M12 16V5M7.5 9.5L12 5l4.5 4.5"/><path d="M5 19h14"/>',
        'chevron' => '<path d="M6 9l6 6 6-6"/>',
        'package' => '<path d="M4.5 8.5L12 4.5l7.5 4v9L12 21.5 4.5 17.5v-9z"/><path d="M12 12.5V21.5M4.5 8.5L12 12.5l7.5-4"/>',
        'truck' => '<path d="M3 16.5V7.5h11v9H3z"/><path d="M14 10.5h4.5l2.5 3.5v2.5H14"/><circle cx="7" cy="17.5" r="1.6"/><circle cx="17" cy="17.5" r="1.6"/>',
        'pin' => '<path d="M12 21s6-5.2 6-10a6 6 0 1 0-12 0c0 4.8 6 10 6 10z"/><circle cx="12" cy="11" r="2"/>',
        'mail' => '<rect x="4" y="6" width="16" height="12" rx="1.5"/><path d="M4 8l8 6 8-6"/>',
        'phone' => '<path d="M8 3.5h3.2l1 4-2.2 1.3a12 12 0 0 0 5.2 5.2L16.5 12l4 1v3.2c0 .8-.6 1.6-1.4 1.8C10.5 19.8 4.2 13.5 2.7 4.9 2.5 4.1 3.3 3.5 4.1 3.5H8z"/>',
        'send' => '<path d="M4 12l16-8-6.5 16-2.2-6.3L4 12z"/>',
        'brush' => '<path d="M14.5 4.5l5 5-8.2 8.2c-.8.8-2.4.6-3.2-.2-.8-.8-1-2.4-.2-3.2L14.5 4.5z"/><path d="M5 20c.5-2 1.8-3.2 3.5-3.5"/>',
        'palette' => '<circle cx="12" cy="12" r="8"/><circle cx="9" cy="10" r="1.1"/><circle cx="14.5" cy="9.5" r="1.1"/><circle cx="15" cy="14" r="1.1"/><circle cx="10" cy="14.5" r="1.1"/>',
        'clock' => '<circle cx="12" cy="12" r="8"/><path d="M12 8v4.5l3 2"/>',
        'layers' => '<path d="M12 4.5L4.5 8.5 12 12.5l7.5-4L12 4.5z"/><path d="M4.5 12.5L12 16.5l7.5-4"/><path d="M4.5 16L12 20l7.5-4"/>',
        'user' => '<circle cx="12" cy="8" r="3.2"/><path d="M5.5 19c.8-3.2 3.3-5 6.5-5s5.7 1.8 6.5 5"/>',
        'quote' => '<path d="M6 16.5c1.8 0 3-1.2 3-3.2 0-3.6-2.6-5.8-5.5-6.3v2.2c1.6.4 2.7 1.6 2.7 3.2H4v4.1h2zm9.5 0c1.8 0 3-1.2 3-3.2 0-3.6-2.6-5.8-5.5-6.3v2.2c1.6.4 2.7 1.6 2.7 3.2h-2.2v4.1h2z"/>',
        'drop' => '<path d="M12 4c3.8 4.4 6 7.4 6 10.2A6 6 0 1 1 6 14.2C6 11.4 8.2 8.4 12 4z"/>',
        'calendar' => '<rect x="4" y="5.5" width="16" height="14.5" rx="1.5"/><path d="M8 3.5v4M16 3.5v4M4 10h16"/>',
        'shield' => '<path d="M12 3.5l7 3v5.7c0 4.2-2.8 7-7 8.8-4.2-1.8-7-4.6-7-8.8V6.5l7-3z"/><path d="M9 12l2 2 4.5-4.5"/>',
        'camera' => '<rect x="3.5" y="7" width="17" height="12.5" rx="2"/><circle cx="12" cy="13.2" r="3.2"/><path d="M8.5 7l1.2-2.2h4.6L15.5 7"/>',
        'heart' => '<path d="M12 19.5s-7-4.4-7-9.2A3.8 3.8 0 0 1 12 7.5a3.8 3.8 0 0 1 7 2.8c0 4.8-7 9.2-7 9.2z"/>',
        'star' => '<path d="M12 4l2.1 5.2H19l-4.2 3.3 1.6 5.3L12 14.8 7.6 17.8l1.6-5.3L5 9.2h4.9L12 4z"/>',
        'spark' => '<path d="M12 3.5v4M12 16.5v4M4.5 12h4M15.5 12h4M7 7l2.2 2.2M14.8 14.8L17 17M17 7l-2.2 2.2M9.2 14.8L7 17"/>',
        'chat' => '<path d="M5 17.5l-1.2 3.2 3.5-1.2c.8.3 1.7.5 2.7.5 4.7 0 8.5-3.4 8.5-7.5S14.7 5 10 5 5 8.4 5 12.5c0 1.8.7 3.4 1.8 4.7z"/>',
        'telegram' => '<g transform="translate(12 12) scale(0.86) translate(-13.1 -11.4)"><path fill="currentColor" stroke="none" d="M21.43 3.53 3.69 10.37c-1.21.47-1.2 1.12-.22 1.41l4.55 1.42 1.76 5.54c.22.78.8.96 1.4.53l2.53-2.16 4.6 3.4c.84.52 1.45.25 1.66-.77L22.8 4.92c.27-1.16-.44-1.7-1.37-1.39z"/></g>',
        'instagram' => '<rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="3.5"/><circle cx="17.2" cy="6.8" r=".9"/>',
        'vk' => '<path d="M4 8.2h2.4c.2 4.2 1.9 6.4 3.4 7V8.2h2.3v3.9c1.5-.2 3-2.2 3.5-3.9H18c-.6 2.2-2.3 4.1-3.6 4.8 1.3.6 3.2 2.3 4 4.8h-2.6c-.6-1.8-2.2-3.4-3.8-3.6v3.6H10c-4.4-.2-6.8-3.3-6.9-8.6z"/>',
        'pinterest' => '<circle cx="12" cy="12" r="8.5"/><path d="M10.4 18.5l1-4.2s-.8-.2-1.1-1.5c0 0-.2-1 .4-1 .6 0 .9.6.9.6s.4-1.1 1.6-1.1c1.6 0 1.4 1.9.7 2.9-.7 1-1.2.3-1.2.3l-.6 2.3c1.9.3 3.4-1.1 3.9-2.7.6-2-.3-4.4-2.9-4.6-2.3-.2-3.8 1.6-3.8 3.4 0 .8.4 1.6.4 1.6"/>',
        'front' => '<rect x="8" y="4" width="8" height="16" rx="2"/><path d="M8 8h8"/>',
        'back' => '<rect x="8" y="4" width="8" height="16" rx="2"/><path d="M10 7h4"/>',
        'sleeve' => '<path d="M9 4h6v4l2 12H7l2-12V4z"/><path d="M9 8h6"/>',
        'gift' => '<rect x="4.5" y="10" width="15" height="10" rx="1"/><path d="M12 10v10M4.5 10h15V8.2H4.5V10z"/><path d="M12 8.2c-2-3-4.5-3.2-5.2-1.4-.5 1.3.6 2.2 5.2 1.4z"/><path d="M12 8.2c2-3 4.5-3.2 5.2-1.4.5 1.3-.6 2.2-5.2 1.4z"/>',
        'home' => '<path d="M4 11.5L12 4.5l8 7"/><path d="M7 10.5V19.5h10v-9"/>',
    ];
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="icon-sprite" aria-hidden="true" focusable="false">';
    foreach ($icons as $id => $markup) {
        echo '<symbol id="i-' . h($id) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' . $markup . '</symbol>';
    }
    echo '</svg>';
}
