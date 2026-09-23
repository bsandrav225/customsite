<?php
declare(strict_types=1);
require_once __DIR__."/auth.php";
if(isAdminLoggedIn()){
    header("Location: index.php");
    exit;
}

$error="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!rate_limit('admin_login:' . client_ip(), 8, 900)) {
        $error = 'Слишком много попыток входа. Попробуйте через 15 минут.';
    } elseif (!csrf_verify()) {
        $error = 'Сессия устарела. Обновите страницу и войдите снова.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $error = 'Введите логин и пароль';
        } else {
            try {
                if (!loginAdmin($username, $password)) {
                    $error = 'Неверный логин или пароль';
                } else {
                    header('Location: index.php');
                    exit;
                }
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — CRAFTD Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="login.css">
</head>
<body class="admin-body admin-login">
    <div class="login-card">
        <a href="../index.php" class="login-brand">CRAFT<span>D</span></a>
        <div class="section-tag">Админ-панель</div>
        <h1 class="login-title">Вход в <em>кабинет</em></h1>
        <p class="login-desc">Войдите, чтобы просматривать и управлять заявками.</p>

        <?php if($error !== ""): ?>
            <div class="form-alert form-alert-error" role="alert">
                <?= h($error) ?>
            </div>
        <?php endif; ?>

        <form class="login-form" action="login.php" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label" for="username">Логин</label>
                <input
                    class="form-input"
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Имя пользователя"
                    required
                    autocomplete="username"
                >
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Пароль</label>
                <input
                    class="form-input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Пароль"
                    required
                    autocomplete="current-password"
                >
            </div>
            <button type="submit" class="btn-primary">
                <span>Войти</span>
            </button>
        </form>

        <a class="login-back" href="../index.php">На главную</a>
    </div>
</body>
</html>
