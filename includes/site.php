<?php
declare(strict_types=1);

/**
 * Категории вещей, которые мастер кастомизирует.
 * Ключи совпадают с фильтрами портфолио и шагом 1 формы заказа.
 */
function site_categories(): array
{
    return [
        'tshirt' => [
            'label' => 'Футболки',
            'short' => 'Футболка',
            'icon' => 'shirt',
            'image' => 'img/cat-tshirt.png',
            'text' => 'Базовая футболка, оверсайз или приталенный крой — роспись на груди, спине или рукаве.',
        ],
        'hoodie' => [
            'label' => 'Худи и свитшоты',
            'short' => 'Худи',
            'icon' => 'hoodie',
            'image' => 'img/cat-hoodie.png',
            'text' => 'Тёплая база для крупной графики: капюшон, спина, рукава.',
        ],
        'denim' => [
            'label' => 'Джинсовые вещи',
            'short' => 'Джинсовка',
            'icon' => 'denim',
            'image' => 'img/cat-denim.png',
            'text' => 'Джинсовки и джинсы — фактура ткани хорошо держит авторскую роспись.',
        ],
        'jacket' => [
            'label' => 'Куртки',
            'short' => 'Куртка',
            'icon' => 'jacket',
            'image' => 'img/cat-jacket.png',
            'text' => 'Куртки и верхняя одежда для заметного кастома на спине и рукавах.',
        ],
        'shoes' => [
            'label' => 'Обувь',
            'short' => 'Обувь',
            'icon' => 'shoe',
            'image' => 'img/cat-shoes.png',
            'text' => 'Кеды и сникеры с росписью мыска, боковин и задника.',
        ],
        'bag' => [
            'label' => 'Сумки и аксессуары',
            'short' => 'Сумка',
            'icon' => 'bag',
            'image' => 'img/cat-bag.png',
            'text' => 'Сумки, шопперы и мелкие аксессуары с индивидуальным рисунком.',
        ],
        'other' => [
            'label' => 'Другие вещи',
            'short' => 'Другое',
            'icon' => 'other',
            'image' => 'img/gifts.png',
            'text' => 'Если вашей вещи нет в списке — опишите её в заявке, обсудим возможность.',
        ],
    ];
}

function site_order_categories(): array
{
    return [
        'tshirt' => 'Футболка',
        'hoodie' => 'Худи',
        'sweatshirt' => 'Свитшот',
        'denim' => 'Джинсовка',
        'jacket' => 'Куртка',
        'shoes' => 'Обувь',
        'bag' => 'Сумка',
        'other' => 'Другое',
    ];
}

function site_socials(): array
{
    $items = [
        'telegram' => ['url' => SOCIAL_TELEGRAM, 'label' => 'Telegram'],
        'instagram' => ['url' => SOCIAL_INSTAGRAM, 'label' => 'Instagram'],
        'vk' => ['url' => SOCIAL_VK, 'label' => 'VK'],
        'pinterest' => ['url' => SOCIAL_PINTEREST, 'label' => 'Pinterest'],
    ];

    return array_filter($items, static fn (array $item): bool => trim($item['url']) !== '');
}

function site_nav(): array
{
    return [
        'index.php' => 'Главная',
        'works.php' => 'Работы',
        'customize.php' => 'Кастомизация',
        'how.php' => 'Процесс',
        'about.php' => 'О мастере',
        'prices.php' => 'Стоимость',
        'faq.php' => 'FAQ',
    ];
}

function site_works(): array
{
    static $works = null;
    if ($works === null) {
        $works = require dirname(__DIR__) . '/data/works.php';
    }
    return $works;
}

function site_find_work(string $id): ?array
{
    foreach (site_works() as $work) {
        if ($work['id'] === $id) {
            return $work;
        }
    }
    return null;
}

function site_faq(): array
{
    return require dirname(__DIR__) . '/data/faq.php';
}

function nav_class(string $file, string $current): string
{
    return basename($current) === $file ? 'is-active' : '';
}
