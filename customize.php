<?php
declare(strict_types=1);

$pageTitle = 'Что можно кастомизировать — CRAFTD';
$pageDescription = 'Футболки, худи, джинсовки, куртки, обувь, сумки и другие вещи. Ручная роспись вашей одежды или новой базы.';
$current = 'customize.php';

require __DIR__ . '/includes/header.php';
$categories = site_categories();
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">Направления</div>
    <h1 class="page-title">Что можно кастомизировать</h1>
    <p class="page-lead">
      Работаю с одеждой и личными вещами. Интерьер не беру —
      здесь только то, что носят.
    </p>
  </div>
</section>

<section class="section customize-page">
  <div class="customize-list">
    <?php foreach ($categories as $key => $cat): ?>
      <article class="customize-row reveal">
        <div class="customize-media media-editorial">
          <img src="<?= h($cat['image']) ?>" alt="<?= h($cat['label']) ?>">
        </div>
        <div class="customize-copy">
          <div class="customize-icon"><?= icon($cat['icon'], 28) ?></div>
          <h2><?= h($cat['label']) ?></h2>
          <p><?= h($cat['text']) ?></p>
          <a class="see-all" href="order.php?item=<?= h($key) ?>">Заказать <?= h($cat['short']) ?> <?= icon('arrow', 16) ?></a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
