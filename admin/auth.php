<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requireAdmin(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function loginAdmin(string $username, string $password): bool
{
    $username = sanitize_text($username, 64);
    if ($username === '' || $password === '') {
        return false;
    }

    try {
        $stmt = getPDO()->prepare('SELECT id, password FROM admin WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
    } catch (PDOException $e) {
        error_log('Admin login DB error: ' . $e->getMessage());
        $source = $e->getPrevious() instanceof PDOException ? $e->getPrevious() : $e;
        throw new RuntimeException(db_public_error($source));
    }

    if (!$admin || !password_verify($password, (string) ($admin['password'] ?? ''))) {
        rate_limit_hit('admin_login:' . client_ip(), 900, 8);
        return false;
    }

    rate_limit_clear('admin_login:' . client_ip());
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_username'] = $username;

    return true;
}

function logoutAdmin(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            (bool) $params['secure'],
            (bool) $params['httponly']
        );
    }

    session_destroy();
}
