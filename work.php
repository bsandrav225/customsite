<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/site.php';

$id = trim((string) ($_GET['id'] ?? ''));
$work = $id !== '' ? site_find_work($id) : null;

if ($work === null) {
    http_response_code(404);
    $pageTitle = 'Работа не найдена — CRAFTD';
    $pageDescription = 'Такой работы в портфолио нет.';
    $current = 'works.php';
    require __DIR__ . '/includes/header.php';
    echo '<section class="page-hero"><div class="page-hero-inner"><h1 class="page-title">Работа не найдена</h1><p class="page-lead">Возможно, ссылка устарела.</p><a class="btn-primary" href="works.php"><span>К работам</span></a></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$categories = site_categories();
$catLabel = $categories[$work['category']]['label'] ?? '';
$pageTitle = $work['title'] . ' — CRAFTD';
$pageDescription = $work['description'];
$current = 'works.php';

require __DIR__ . '/includes/header.php';
?>

<article class="work-detail">
  <div class="work-detail-gallery">
    <?php foreach ($work['images'] as $i => $src): ?>
      <figure class="media-editorial work-photo<?= $i === 0 ? ' is-main' : '' ?>">
        <img src="<?= h($src) ?>" alt="<?= h($work['title']) ?><?= $i > 0 ? ', фото ' . ($i + 1) : '' ?>">
      </figure>
    <?php endforeach; ?>
  </div>

  <div class="work-detail-info">
    <p class="section-tag"><?= h($catLabel) ?></p>
    <h1 class="page-title"><?= h($work['title']) ?></h1>
    <p class="page-lead"><?= h($work['description']) ?></p>

    <?php if (!empty($work['techniques'])): ?>
      <h2 class="subhead">Техники</h2>
      <ul class="tag-list">
        <?php foreach ($work['techniques'] as $tech): ?>
          <li><?= h($tech) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <p class="work-note">
      Эту работу можно взять как вдохновение для нового заказа.
      Точную копию не повторяю — сделаю новую вещь в том же настроении.
    </p>

    <div class="work-actions">
      <a class="btn-primary" href="order.php?ref=<?= h($work['id']) ?>">
        <span>Хочу похожий кастом</span>
      </a>
      <a class="btn-ghost" href="works.php"><span>К другим работам</span></a>
    </div>
  </div>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>
