<?php
declare(strict_types=1);

$pageTitle = 'FAQ — CRAFTD';
$pageDescription = 'Ответы на частые вопросы о кастомизации одежды: отправка вещи, СДЭК, сроки, уход и можно ли повторить работу из портфолио.';
$current = 'faq.php';

require __DIR__ . '/includes/header.php';
$faq = site_faq();
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">FAQ</div>
    <h1 class="page-title">Вопросы и ответы</h1>
    <p class="page-lead">Если не нашли свой вопрос — напишите его в заявке. Так даже проще.</p>
  </div>
</section>

<section class="section">
  <div class="faq-list">
    <?php foreach ($faq as $item): ?>
      <details class="faq-item reveal">
        <summary>
          <span><?= h($item['q']) ?></span>
          <?= icon('chevron', 20) ?>
        </summary>
        <div class="faq-answer"><p><?= h($item['a']) ?></p></div>
      </details>
    <?php endforeach; ?>
  </div>
  <div class="page-cta">
    <a href="order.php" class="btn-primary"><span>Оставить заявку</span></a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
