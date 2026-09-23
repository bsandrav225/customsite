<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once __DIR__ . '/site.php';
require_once __DIR__ . '/icons.php';

$pageTitle = $pageTitle ?? 'CRAFTD — ручная кастомизация одежды';
$pageDescription = $pageDescription ?? 'Студия ручной кастомизации одежды и вещей. Принесите свою идею или вещь — получите уникальный кастом.';
$current = $current ?? basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$bodyClass = $bodyClass ?? '';
$extraHead = $extraHead ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= h($pageTitle) ?></title>
  <meta name="description" content="<?= h($pageDescription) ?>" />
  <meta name="theme-color" content="#1a3a2a" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="studio.css" />
  <?= $extraHead ?>
</head>
<body<?= $bodyClass !== '' ? ' class="' . h($bodyClass) . '"' : '' ?>>
  <?php icons_sprite(); ?>

  <div class="cursor" id="cursor"></div>
  <div class="cursor-ring" id="cursorRing"></div>

  <a class="skip-link" href="#main">К содержанию</a>

  <header>
    <nav aria-label="Основная навигация">
      <a href="index.php" class="logo">CRAFT<span>D</span></a>

      <button class="nav-toggle" type="button" aria-label="Открыть меню" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <ul class="nav-links">
        <?php foreach (site_nav() as $href => $label): ?>
          <li>
            <a href="<?= h($href) ?>"<?= nav_class($href, $current) !== '' ? ' class="' . nav_class($href, $current) . '" aria-current="page"' : '' ?>>
              <?= h($label) ?>
            </a>
          </li>
        <?php endforeach; ?>
        <li>
          <a href="order.php" class="nav-cta<?= nav_class('order.php', $current) !== '' ? ' is-active' : '' ?>">Заказать кастом</a>
        </li>
      </ul>
    </nav>
  </header>

  <div class="nav-overlay" id="navOverlay" aria-hidden="true"></div>

  <main id="main">
