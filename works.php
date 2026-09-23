<?php
declare(strict_types=1);

$pageTitle = 'Работы — CRAFTD';
$pageDescription = 'Портфолио ручной кастомизации: футболки, худи, джинсовки, обувь и аксессуары. Смотрите работы и заказывайте похожий кастом.';
$current = 'works.php';

require __DIR__ . '/includes/header.php';

$works = site_works();
$categories = site_categories();
$filter = $_GET['cat'] ?? 'all';
$validFilters = array_merge(['all'], array_keys($categories));
if (!in_array($filter, $validFilters, true)) {
    $filter = 'all';
}
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">Портфолио</div>
    <h1 class="page-title">Работы</h1>
    <p class="page-lead">
      Каждая вещь собрана вручную. Можно смотреть как вдохновение —
      и заказать похожий кастом, а не копию.
    </p>
  </div>
</section>

<section class="section works-page">
  <div class="works-filters" role="tablist" aria-label="Фильтр работ">
    <a class="chip<?= $filter === 'all' ? ' is-active' : '' ?>" href="works.php">Все работы</a>
    <?php foreach ($categories as $key => $cat): ?>
      <?php if ($key === 'other') continue; ?>
      <a class="chip<?= $filter === $key ? ' is-active' : '' ?>" href="works.php?cat=<?= h($key) ?>"><?= h($cat['label']) ?></a>
    <?php endforeach; ?>
  </div>

  <div class="works-grid" id="worksGrid">
    <?php
    $shown = 0;
    foreach ($works as $work):
      if ($filter !== 'all' && $work['category'] !== $filter) {
          continue;
      }
      $shown++;
      $catLabel = $categories[$work['category']]['label'] ?? '';
    ?>
      <a class="work-card" href="work.php?id=<?= h($work['id']) ?>" data-category="<?= h($work['category']) ?>">
        <div class="work-card-media media-editorial">
          <img src="<?= h($work['images'][0]) ?>" alt="<?= h($work['title']) ?>">
        </div>
        <div class="work-card-body">
          <p class="work-card-cat"><?= h($catLabel) ?></p>
          <h2 class="work-card-title"><?= h($work['title']) ?></h2>
        </div>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($shown === 0): ?>
    <p class="empty-note">В этой категории пока нет опубликованных работ. Можно посмотреть все или описать идею в заявке.</p>
    <a href="order.php" class="btn-primary"><span>Заказать кастомизацию</span></a>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
