<?php
declare(strict_types=1);

$pageTitle = 'О мастере — CRAFTD';
$pageDescription = 'Кто стоит за CRAFTD: ручная роспись одежды, личный подход и работа с вашей вещью или новой базой.';
$current = 'about.php';

require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">Студия</div>
    <h1 class="page-title">О мастере</h1>
    <p class="page-lead">
      CRAFTD — это не бренд с конвейером. Это мастерская, где одежда
      становится личной историей.
    </p>
  </div>
</section>

<section class="section about-page">
  <div class="about-grid">
    <figure class="about-photo media-editorial reveal">
      <img src="img/about-atelier.png" alt="Работа в мастерской: роспись одежды вручную">
    </figure>
    <div class="about-copy reveal">
      <h2>Коротко обо мне</h2>
      <p>
        Я занимаюсь ручной кастомизацией одежды и вещей: роспись по ткани,
        графика, портреты, надписи и спокойные минималистичные знаки.
        Мне важно, чтобы вещь оставалась носибельной — красивой не только на фото.
      </p>
      <p>
        Часто ко мне приходят с уже любимой футболкой, джинсовкой или сумкой.
        Иногда — без вещи, но с точным ощущением, какой она должна быть.
        Оба сценария рабочие. Главное — разговор до кисти.
      </p>
    </div>
  </div>

  <div class="about-blocks">
    <article class="about-block reveal">
      <?= icon('clock', 26) ?>
      <h3>Опыт</h3>
      <p>
        Работаю с денимом, хлопком, футером, обувью и аксессуарами.
        Смотрю не только на идею, но и на ткань: не всё, что красиво в референсе,
        хорошо ляжет на конкретную вещь.
      </p>
    </article>
    <article class="about-block reveal">
      <?= icon('user', 26) ?>
      <h3>Подход</h3>
      <p>
        Без шаблонных принтов «из каталога». Сначала слушаю, потом предлагаю.
        Если идея слабая или не ляжет на крой — скажу. Лучше честный отказ,
        чем вещь, которую стыдно отдать.
      </p>
    </article>
    <article class="about-block reveal">
      <?= icon('palette', 26) ?>
      <h3>Материалы</h3>
      <p>
        Краски и контуры для ткани, закрепление работы, аккуратная подготовка
        поверхности. Интерьер и постеры не делаю — фокус на одежде и личных вещах.
      </p>
    </article>
  </div>

  <div class="about-note reveal">
    <h2>Почему это ручная работа</h2>
    <p>
      Кастом нельзя «выбрать размер и оплатить». Рисунок зависит от швов,
      посадки, цвета базы и того, как человек носит вещь. Поэтому заявка —
      это начало разговора, а не корзина магазина.
    </p>
  </div>

  <div class="process-thumbs">
    <figure class="media-editorial reveal">
      <img src="img/process-painting.png" alt="Роспись джинсовки кистью">
    </figure>
    <figure class="media-editorial reveal">
      <img src="img/process-studio.png" alt="Материалы и вещь в работе">
    </figure>
    <figure class="media-editorial reveal">
      <img src="img/process-sketch.png" alt="Эскиз перед росписью">
    </figure>
  </div>

  <div class="page-cta">
    <a href="order.php" class="btn-primary"><span>Обсудить заказ</span></a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
