<?php
declare(strict_types=1);

$pageTitle = 'Заявка отправлена — CRAFTD';
$pageDescription = 'Спасибо, заявка на кастомизацию получена.';
$current = 'order.php';
$bodyClass = 'constructor-page';

require __DIR__ . '/includes/header.php';
?>

<section class="success-page">
  <div class="form-alert form-alert-success success-glow" role="status">
    <span class="form-alert-neon-dot" aria-hidden="true"></span>
    <div class="form-alert-success-inner success-card">
      <span class="form-alert-icon" aria-hidden="true"><?= icon('check', 28) ?></span>
      <div>
        <h1 class="constructor-title">Спасибо! Заявка отправлена.</h1>
        <p class="constructor-subtitle">
          Я изучу вашу идею и свяжусь с вами для обсуждения деталей.
          Отправлять вещь пока не нужно — сначала подтвердим заказ.
        </p>
        <div class="hero-actions">
          <a href="works.php" class="btn-primary"><span>Посмотреть другие работы</span></a>
          <a href="index.php" class="btn-ghost btn-ghost--light">На главную</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
