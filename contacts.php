<?php
declare(strict_types=1);

$pageTitle = 'Контакты — CRAFTD';
$pageDescription = 'Как связаться со студией CRAFTD и оставить заявку на кастомизацию одежды.';
$current = 'contacts.php';

require __DIR__ . '/includes/header.php';
$socials = site_socials();
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">Контакты</div>
    <h1 class="page-title">Как связаться</h1>
    <p class="page-lead">
      Удобнее всего — заявка. В ней уже есть идея, фото и способ связи,
      поэтому разговор начинается быстрее.
    </p>
  </div>
</section>

<section class="section contacts-page">
  <div class="contacts-grid">
    <article class="contact-card reveal">
      <?= icon('send', 28) ?>
      <h2>Заявка</h2>
      <p>Основной способ. Пять коротких шагов — и я вижу задачу целиком.</p>
      <a class="btn-primary" href="order.php"><span>Заказать кастом</span></a>
    </article>

    <?php if (SITE_EMAIL !== ''): ?>
      <article class="contact-card reveal">
        <?= icon('mail', 28) ?>
        <h2>Почта</h2>
        <p><a href="mailto:<?= h(SITE_EMAIL) ?>"><?= h(SITE_EMAIL) ?></a></p>
      </article>
    <?php endif; ?>

    <?php if (SITE_PHONE !== ''): ?>
      <article class="contact-card reveal">
        <?= icon('phone', 28) ?>
        <h2>Телефон</h2>
        <p><a href="tel:<?= h(SITE_PHONE) ?>"><?= h(SITE_PHONE) ?></a></p>
      </article>
    <?php endif; ?>

    <?php if (SITE_CITY !== ''): ?>
      <article class="contact-card reveal">
        <?= icon('pin', 28) ?>
        <h2>Город</h2>
        <p><?= h(SITE_CITY) ?>. Личная передача вещи — по договорённости.</p>
      </article>
    <?php endif; ?>
  </div>

  <?php if ($socials): ?>
    <div class="reveal">
      <h2 class="subhead">Социальные сети</h2>
      <div class="social-links social-links--lg">
        <?php foreach ($socials as $name => $item): ?>
          <a class="social-btn" href="<?= h($item['url']) ?>" target="_blank" rel="noopener noreferrer">
            <?= icon($name, 22) ?>
            <span><?= h($item['label']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php else: ?>
    <p class="empty-note reveal">
      Ссылки на социальные сети появятся здесь, когда владелец сайта их укажет
      в настройках. До этого все разговоры идут через заявку.
    </p>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
