<?php
/*
 * ============================================================
 * Общие настройки проекта CRAFTD.
 *
 * Секреты БД НЕ хранятся в этом файле (он попадает в Git).
 * Скопируйте config.local.php.example → config.local.php
 * и укажите данные MySQL (для XAMPP и для хостинга одинаково).
 * ============================================================
 */

declare(strict_types=1);

if (is_readable(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
}

if (
    !defined('DB_HOST')
    || !defined('DB_NAME')
    || !defined('DB_USER')
    || !defined('DB_PASS')
) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Не найден файл config.local.php с настройками БД.\n\n"
        . "1. Скопируйте config.local.php.example → config.local.php\n"
        . "2. Укажите DB_HOST, DB_NAME, DB_USER, DB_PASS\n"
        . "3. Для XAMPP обычно: host 127.0.0.1, user root, пароль пустой, БД castomize\n";
    exit;
}

if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', 'utf8mb4');
}

if (!defined('SOCIAL_TELEGRAM')) {
    define('SOCIAL_TELEGRAM', 'https://t.me/b_sandra_v');
}
if (!defined('SOCIAL_INSTAGRAM')) {
    define('SOCIAL_INSTAGRAM', '');
}
if (!defined('SOCIAL_VK')) {
    define('SOCIAL_VK', 'https://vk.ru/b_sandra_v');
}
if (!defined('SOCIAL_PINTEREST')) {
    define('SOCIAL_PINTEREST', '');
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'CRAFTD');
}
if (!defined('SITE_EMAIL')) {
    define('SITE_EMAIL', 'bsandrav@yandex.ru');
}
if (!defined('SITE_PHONE')) {
    define('SITE_PHONE', '');
}
if (!defined('SITE_CITY')) {
    define('SITE_CITY', '');
}

require_once __DIR__ . '/includes/security.php';
security_bootstrap();

function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $hosts = [DB_HOST];
        if (DB_HOST === '127.0.0.1') {
            $hosts[] = 'localhost';
        } elseif (DB_HOST === 'localhost') {
            $hosts[] = '127.0.0.1';
        }

        $lastError = null;
        foreach (array_unique($hosts) as $host) {
            $dsn = 'mysql:host=' . $host . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            try {
                $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                $lastError = null;
                break;
            } catch (PDOException $e) {
                $lastError = $e;
            }
        }

        if ($pdo === null && $lastError instanceof PDOException) {
            error_log('Database connection failed: ' . $lastError->getMessage());
            throw new PDOException(
                db_public_error($lastError),
                (int) $lastError->getCode(),
                $lastError
            );
        }
    }

    return $pdo;
}

function db_public_error(PDOException $e): string
{
    $raw = $e->getMessage();

    if (str_contains($raw, '1045') || str_contains($raw, 'Access denied')) {
        return 'Неверный логин или пароль MySQL. Проверьте DB_USER и DB_PASS в config.local.php.';
    }
    if (str_contains($raw, '1049') || str_contains($raw, 'Unknown database')) {
        return 'База с таким именем не найдена. Создайте БД и импортируйте database/schema.sql, затем проверьте DB_NAME в config.local.php.';
    }
    if (str_contains($raw, '2002') || str_contains($raw, '2003')) {
        return 'Сервер MySQL недоступен. Для XAMPP запустите MySQL и укажите DB_HOST = 127.0.0.1.';
    }
    if (str_contains($raw, '42S02') || str_contains($raw, "doesn't exist")) {
        return 'Таблицы не найдены. В phpMyAdmin импортируйте файл database/schema.sql.';
    }

    return 'Не удалось подключиться к базе данных. Проверьте config.local.php и импорт database/schema.sql.';
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
