<?php
declare(strict_types=1);

/**
 * Общие функции безопасности CRAFTD.
 * Подключается из config.php на каждом запросе.
 */

function security_bootstrap(): void
{
  security_headers();
  security_session_start();
}

function security_is_https(): bool
{
  if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    return true;
  }

  return (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
}

function security_session_start(): void
{
  if (session_status() === PHP_SESSION_ACTIVE) {
    return;
  }

  ini_set('session.use_strict_mode', '1');
  ini_set('session.use_only_cookies', '1');
  ini_set('session.cookie_httponly', '1');
  ini_set('session.cookie_samesite', 'Lax');

  if (security_is_https()) {
    ini_set('session.cookie_secure', '1');
  }

  session_name('CRAFTDSESSID');
  session_start();

  if (!isset($_SESSION['_security_init'])) {
    session_regenerate_id(true);
    $_SESSION['_security_init'] = time();
  }
}

function security_headers(): void
{
  if (headers_sent()) {
    return;
  }

  header('X-Content-Type-Options: nosniff');
  header('X-Frame-Options: SAMEORIGIN');
  header('Referrer-Policy: strict-origin-when-cross-origin');
  header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
  header('X-Permitted-Cross-Domain-Policies: none');

  if (security_is_https()) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
  }

  $csp = implode('; ', [
    "default-src 'self'",
    "img-src 'self' data: blob:",
    "style-src 'self' 'unsafe-inline'",
    "script-src 'self'",
    "font-src 'self' data:",
    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'self'",
    "frame-ancestors 'self'",
  ]);
  header('Content-Security-Policy: ' . $csp);
}

function csrf_token(): string
{
  security_session_start();

  if (empty($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
  }

  return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
  return '<input type="hidden" name="_csrf" value="' . h(csrf_token()) . '">';
}

function csrf_verify(?string $token = null): bool
{
  security_session_start();
  $token = $token ?? (string) ($_POST['_csrf'] ?? $_GET['_csrf'] ?? '');

  if ($token === '' || empty($_SESSION['_csrf_token']) || !is_string($_SESSION['_csrf_token'])) {
    return false;
  }

  return hash_equals($_SESSION['_csrf_token'], $token);
}

function require_csrf(): void
{
  if (!csrf_verify()) {
    http_response_code(403);
    exit('Недействительный запрос. Обновите страницу и попробуйте снова.');
  }
}

function rate_limit(string $key, int $maxAttempts = 5, int $windowSeconds = 900): bool
{
  $file = rate_limit_file($key);
  if ($file === null || !is_file($file)) {
    return true;
  }

  $now = time();
  $data = rate_limit_read($file);

  if (($data['blocked_until'] ?? 0) > $now) {
    return false;
  }

  $attempts = rate_limit_recent($data['attempts'] ?? [], $now, $windowSeconds);

  return count($attempts) < $maxAttempts;
}

function rate_limit_hit(string $key, int $windowSeconds = 900, int $maxAttempts = 8): void
{
  $dir = dirname(__DIR__) . '/storage/rate_limit';
  if (!is_dir($dir) && !mkdir($dir, 0750, true) && !is_dir($dir)) {
    return;
  }

  $file = $dir . '/' . hash('sha256', $key) . '.json';
  $now = time();
  $data = is_file($file) ? rate_limit_read($file) : ['attempts' => [], 'blocked_until' => 0];
  $attempts = rate_limit_recent($data['attempts'] ?? [], $now, $windowSeconds);
  $attempts[] = $now;

  $data['attempts'] = $attempts;
  $data['blocked_until'] = count($attempts) >= $maxAttempts ? ($now + $windowSeconds) : 0;
  file_put_contents($file, json_encode($data), LOCK_EX);
}

function rate_limit_clear(string $key): void
{
  $file = rate_limit_file($key);
  if ($file !== null && is_file($file)) {
    unlink($file);
  }
}

function rate_limit_file(string $key): ?string
{
  $dir = dirname(__DIR__) . '/storage/rate_limit';
  if (!is_dir($dir)) {
    return null;
  }

  return $dir . '/' . hash('sha256', $key) . '.json';
}

function rate_limit_read(string $file): array
{
  $data = ['attempts' => [], 'blocked_until' => 0];
  $raw = file_get_contents($file);
  if ($raw === false) {
    return $data;
  }

  $decoded = json_decode($raw, true);
  return is_array($decoded) ? array_merge($data, $decoded) : $data;
}

function rate_limit_recent(array $attempts, int $now, int $windowSeconds): array
{
  return array_values(array_filter(
    $attempts,
    static fn ($ts) => is_int($ts) && ($now - $ts) < $windowSeconds
  ));
}

function client_ip(): string
{
  return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function sanitize_text(?string $value, int $maxLength): string
{
  $value = trim((string) $value);
  if ($value === '') {
    return '';
  }

  if (function_exists('mb_substr')) {
    return mb_substr($value, 0, $maxLength);
  }

  return substr($value, 0, $maxLength);
}

function is_safe_uploaded_image(string $tmpPath): bool
{
  if (!is_uploaded_file($tmpPath)) {
    return false;
  }

  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime = $finfo->file($tmpPath) ?: '';
  $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

  if (!in_array($mime, $allowed, true)) {
    return false;
  }

  $imageInfo = @getimagesize($tmpPath);
  if ($imageInfo === false) {
    return false;
  }

  $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF];
  return in_array((int) $imageInfo[2], $allowedTypes, true);
}

function admin_require_post(): void
{
  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
  }
}
