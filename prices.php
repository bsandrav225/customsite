<?php
declare(strict_types=1);

$pageTitle = 'Стоимость — CRAFTD';
$pageDescription = 'От чего зависит цена кастомизации: размер рисунка, сложность, зоны, тип вещи и срочность. Оценка после заявки.';
$current = 'prices.php';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">Стоимость</div>
    <h1 class="page-title">Сколько стоит кастом</h1>
    <p class="page-lead">
      Фиксированного прайса на «любую футболку» нет — работа индивидуальная.
      Ниже — от чего складывается сумма и какие бывают масштабы.
    </p>
  </div>
</section>

<section class="section prices-page">
  <div class="price-factors reveal">
    <h2>От чего зависит стоимость</h2>
    <ul class="factor-list">
      <li><?= icon('layers', 20) ?> <span>Размер рисунка и количество деталей</span></li>
      <li><?= icon('spark', 20) ?> <span>Сложность: портрет, леттеринг, плотная графика</span></li>
      <li><?= icon('shirt', 20) ?> <span>Число зон: грудь, спина, рукав, обувь целиком</span></li>
      <li><?= icon('package', 20) ?> <span>Тип вещи и состояние ткани</span></li>
      <li><?= icon('bag', 20) ?> <span>Нужно ли покупать новую базу</span></li>
      <li><?= icon('clock', 20) ?> <span>Срочность</span></li>
    </ul>
  </div>

  <div class="price-tiers">
    <article class="price-card reveal">
      <p class="price-card-label">Небольшой кастом</p>
      <h2>Знак, надпись, акцент</h2>
      <p>Одна зона, спокойная графика или небольшой мотив. Ориентир обсуждается после эскиза идеи.</p>
      <p class="price-from">от · рассчитывается индивидуально</p>
    </article>
    <article class="price-card is-featured reveal">
      <p class="price-card-label">Средний дизайн</p>
      <h2>Сюжет на груди или спине</h2>
      <p>Заметная композиция, несколько слоёв, работа с цветом. Самый частый формат.</p>
      <p class="price-from">обсуждается после оценки идеи</p>
    </article>
    <article class="price-card reveal">
      <p class="price-card-label">Полная кастомизация</p>
      <h2>Крупная роспись вещи</h2>
      <p>Несколько зон, плотная детализация, обувь целиком или сложный портрет.</p>
      <p class="price-from">индивидуально</p>
    </article>
  </div>

  <p class="price-disclaimer">
    Суммы на сайте намеренно не выдуманы. После заявки я смотрю референсы
    и называю понятный ориентир — до начала работы.
  </p>

  <div class="page-cta">
    <a href="order.php" class="btn-primary"><span>Узнать стоимость моей идеи</span></a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
