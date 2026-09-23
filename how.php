<?php
declare(strict_types=1);

$pageTitle = 'Как это работает — CRAFTD';
$pageDescription = 'Пять шагов ручной кастомизации: заявка, обсуждение, передача вещи или покупка базы, роспись и получение готовой работы.';
$current = 'how.php';

require __DIR__ . '/includes/header.php';

$steps = [
    [
        'num' => '01',
        'title' => 'Вы оставляете заявку',
        'text' => 'Рассказываете о вещи и своей идее. Можно приложить фото референсов или своей одежды — чем яснее запрос, тем точнее ответ.',
        'icon' => 'send',
        'image' => 'img/process-sketch.png',
    ],
    [
        'num' => '02',
        'title' => 'Мы обсуждаем детали',
        'text' => 'Я уточняю пожелания, предлагаю варианты композиции и говорю, что реалистично сделать на этой ткани. Никакого автозаказа без разговора.',
        'icon' => 'chat',
        'image' => 'img/process-studio.png',
    ],
    [
        'num' => '03',
        'title' => 'Вы передаёте вещь',
        'text' => 'Или я самостоятельно приобретаю подходящую базу: размер, цвет и посадка согласовываются заранее. Передать можно лично, почтой или через СДЭК.',
        'icon' => 'package',
        'image' => 'img/cat-denim.png',
    ],
    [
        'num' => '04',
        'title' => 'Создаётся кастом',
        'text' => 'Вещь вручную превращается в уникальный предмет. Это не печать тиражом и не аппликация из каталога — рисунок появляется на ткани шаг за шагом.',
        'icon' => 'brush',
        'image' => 'img/process-painting.png',
    ],
    [
        'num' => '05',
        'title' => 'Вы получаете готовую работу',
        'text' => 'Готовая вещь уходит обратно удобным способом. Вместе с ней — короткие правила ухода, чтобы роспись жила долго.',
        'icon' => 'truck',
        'image' => 'img/work-hoodie-night.png',
    ],
];
?>

<section class="page-hero">
  <div class="page-hero-inner">
    <div class="section-tag">Процесс</div>
    <h1 class="page-title">Как это работает</h1>
    <p class="page-lead">
      Один понятный путь: от идеи до вещи, которую можно носить.
      Без корзины и без «оформить заказ в один клик».
    </p>
  </div>
</section>

<section class="section process-page">
  <ol class="process-list">
    <?php foreach ($steps as $i => $step): ?>
      <li class="process-item reveal">
        <?php if ($i > 0): ?>
          <div class="process-connector" aria-hidden="true">
            <span class="form-alert-neon-dot process-connector-dot"></span>
          </div>
        <?php endif; ?>
        <div class="process-copy">
          <div class="process-meta">
            <span class="process-num"><?= h($step['num']) ?></span>
            <?= icon($step['icon'], 26) ?>
          </div>
          <h2><?= h($step['title']) ?></h2>
          <p><?= h($step['text']) ?></p>
        </div>
        <figure class="process-media media-editorial">
          <img src="<?= h($step['image']) ?>" alt="<?= h($step['title']) ?>">
        </figure>
      </li>
    <?php endforeach; ?>
  </ol>

  <div class="page-cta">
    <a href="order.php" class="btn-primary"><span>Оставить заявку</span></a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
