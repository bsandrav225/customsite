<?php
declare(strict_types=1);

$pageTitle = 'CRAFTD — ручная кастомизация одежды';
$pageDescription = 'Твоя вещь. Твоя идея. Уникальный дизайн. Студия ручной кастомизации одежды: портфолио, процесс и заявка на индивидуальный кастом.';
$current = 'index.php';

require __DIR__ . '/includes/header.php';

$works = array_slice(site_works(), 0, 4);
$categories = site_categories();
$faqPreview = array_slice(site_faq(), 0, 5);
$socials = site_socials();
?>

<section class="hero">
  <div class="hero-left">
    <div class="hero-tag">Ручная кастомизация</div>
    <h1 class="hero-title">
      Твоя вещь.<br>
      Твоя идея.<br>
      <em>Уникальный</em><br>
      дизайн.
    </h1>
    <p class="hero-desc">
      Не магазин готовых футболок. Студия, где из вашей одежды — или новой базы —
      получается вещь, которой нет ни у кого другого.
    </p>
    <div class="hero-actions">
      <a href="order.php" class="btn-primary"><span>Заказать кастомизацию</span></a>
      <a href="works.php" class="btn-ghost">Смотреть работы</a>
    </div>
  </div>

  <div class="hero-right hero-right--photos">
    <div class="geo geo-1"></div>
    <div class="geo geo-2"></div>
    <div class="geo geo-3"></div>
    <figure class="hero-photo hero-photo-main">
      <img src="img/jacket.png" alt="Джинсовка с ручной росписью — портрет таксы на спине">
    </figure>
    <figure class="hero-photo hero-photo-a">
      <img src="img/work-tshirt-line.png" alt="Футболка с линейным портретом">
    </figure>
    <figure class="hero-photo hero-photo-b">
      <img src="img/cat-bag.png" alt="Сумка с авторской росписью">
    </figure>
    <div class="hero-big-num">01</div>
  </div>
</section>

<div class="marquee-wrap" aria-hidden="true">
  <div class="marquee-track">
    <?php
    $marquee = ['РУЧНАЯ РАБОТА', 'ТВОЙ ДИЗАЙН', 'ЕДИНСТВЕННАЯ ВЕЩЬ', 'КАСТОМ ПОД ТЕБЯ', 'АВТОРСКАЯ РОСПИСЬ'];
    for ($i = 0; $i < 2; $i++):
      foreach ($marquee as $item):
    ?>
      <span class="marquee-item"><?= h($item) ?></span>
      <span class="marquee-item accent"><?= icon('spark', 18) ?></span>
    <?php
      endforeach;
    endfor;
    ?>
  </div>
</div>

<section class="products-section" id="featured">
  <div class="section-header reveal">
    <div>
      <div class="section-tag">Портфолио</div>
      <h2 class="section-title">Избранные<br><em>работы</em></h2>
    </div>
    <a href="works.php" class="see-all">Все работы <?= icon('arrow', 16) ?></a>
  </div>

  <div class="works-grid works-grid--home">
    <?php foreach ($works as $work): ?>
      <a class="work-card reveal" href="work.php?id=<?= h($work['id']) ?>">
        <div class="work-card-media media-editorial">
          <img src="<?= h($work['images'][0]) ?>" alt="<?= h($work['title']) ?>">
        </div>
        <div class="work-card-body">
          <p class="work-card-cat"><?= h(site_categories()[$work['category']]['label'] ?? '') ?></p>
          <h3 class="work-card-title"><?= h($work['title']) ?></h3>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="customize">
  <div class="section-header reveal">
    <div>
      <div class="section-tag">Направления</div>
      <h2 class="section-title">Что можно<br><em>кастомизировать</em></h2>
    </div>
    <a href="customize.php" class="see-all">Подробнее <?= icon('arrow', 16) ?></a>
  </div>

  <div class="categories-grid categories-grid--studio">
    <?php foreach ($categories as $key => $cat): ?>
      <a class="cat-card reveal" href="order.php?item=<?= h($key) ?>">
        <div class="cat-bg cat-bg-<?= h($key) ?>"></div>
        <img class="cat-img" src="<?= h($cat['image']) ?>" alt="<?= h($cat['label']) ?>">
        <div class="cat-overlay"></div>
        <div class="cat-info">
          <div class="cat-name"><?= h($cat['label']) ?></div>
          <div class="cat-count">Оставить заявку</div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="how-section" id="how">
  <div style="position:relative;z-index:1">
    <div class="section-tag reveal">Процесс</div>
    <h2 class="section-title reveal">
      Как это<br><em style="color:var(--green-bright)">работает</em>
    </h2>
  </div>

  <div class="steps-grid steps-grid--five">
    <div class="steps-line" aria-hidden="true">
      <span class="form-alert-neon-dot steps-line-dot"></span>
    </div>
    <?php
    $homeSteps = [
      ['01', 'Заявка', 'Рассказываете о вещи и идее — своими словами, без конструктора.'],
      ['02', 'Обсуждение', 'Уточняю детали и предлагаю, как это может выглядеть на ткани.'],
      ['03', 'Вещь', 'Вы передаёте свою — или я подбираю новую базу.'],
      ['04', 'Кастом', 'Роспись вручную. Без печати тиражом и без чужого шаблона.'],
      ['05', 'Готово', 'Забираете лично или получаете обратно удобной доставкой.'],
    ];
    foreach ($homeSteps as $i => $step):
    ?>
      <div class="step-card reveal">
        <div class="step-num step-num-<?= ($i % 4) + 1 ?>"><?= $step[0] ?></div>
        <div class="step-title"><?= h($step[1]) ?></div>
        <div class="step-desc"><?= h($step[2]) ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="how-cta reveal">
    <a href="how.php" class="btn-ghost btn-ghost--light">Подробнее о процессе</a>
  </div>
</section>

<section class="section advantages-section">
  <div class="section-header reveal">
    <div>
      <div class="section-tag">Подход</div>
      <h2 class="section-title">Почему это<br><em>ручная</em> работа</h2>
    </div>
  </div>
  <div class="adv-grid">
    <article class="adv-card reveal">
      <?= icon('brush', 28) ?>
      <h3>Авторский рисунок</h3>
      <p>Каждая вещь расписывается вручную. Нет тиража, нет «ещё одной такой же с полки».</p>
    </article>
    <article class="adv-card reveal">
      <?= icon('user', 28) ?>
      <h3>Личный разговор</h3>
      <p>Сначала идея и референсы, потом эскиз и работа. Не форма «добавить в корзину».</p>
    </article>
    <article class="adv-card reveal">
      <?= icon('layers', 28) ?>
      <h3>Два сценария</h3>
      <p>Приносите свою вещь — или я подберу новую базу нужного размера и цвета.</p>
    </article>
    <article class="adv-card reveal">
      <?= icon('shield', 28) ?>
      <h3>Краски для ткани</h3>
      <p>Работа закрепляется и рассчитана на носку. Уход простой — расскажу при выдаче.</p>
    </article>
  </div>
</section>

<section class="testi-section" id="reviews">
  <div class="section-header reveal">
    <div>
      <div class="section-tag">Отзывы</div>
      <h2 class="section-title">Голоса<br><em>клиентов</em></h2>
    </div>
  </div>
  <div class="reviews-placeholder reveal">
    <div class="quote-mark" aria-hidden="true"><?= icon('quote', 40) ?></div>
    <p>
      Здесь появятся живые отзывы — когда вы разрешите их показать.
      Пока можно посмотреть работы и написать, если хочется обсудить свою идею.
    </p>
    <a href="order.php" class="btn-ghost">Оставить заявку</a>
  </div>
</section>

<section class="cta-banner glow-frame">
  <span class="form-alert-neon-dot" aria-hidden="true"></span>
  <div class="cta-banner-inner reveal">
    <h2 class="section-title">Готовы обсудить<br><em>свой кастом</em>?</h2>
    <p>Опишите идею, приложите референсы — я отвечу и предложу, как это сделать на вещи.</p>
    <a href="order.php" class="btn-primary"><span>Заказать кастомизацию</span></a>
  </div>
</section>

<?php if ($socials): ?>
<section class="section social-block">
  <div class="reveal">
    <div class="section-tag">Сети</div>
    <h2 class="section-title">Больше работ —<br><em>в социальных сетях</em></h2>
    <div class="social-links social-links--lg">
      <?php foreach ($socials as $name => $item): ?>
        <a class="social-btn" href="<?= h($item['url']) ?>" target="_blank" rel="noopener noreferrer">
          <?= icon($name, 22) ?>
          <span><?= h($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section faq-preview" id="faq">
  <div class="section-header reveal">
    <div>
      <div class="section-tag">Вопросы</div>
      <h2 class="section-title">Коротко<br><em>по делу</em></h2>
    </div>
    <a href="faq.php" class="see-all">Все вопросы <?= icon('arrow', 16) ?></a>
  </div>
  <div class="faq-list">
    <?php foreach ($faqPreview as $i => $item): ?>
      <details class="faq-item reveal"<?= $i === 0 ? ' open' : '' ?>>
        <summary>
          <span><?= h($item['q']) ?></span>
          <?= icon('chevron', 20) ?>
        </summary>
        <div class="faq-answer"><p><?= h($item['a']) ?></p></div>
      </details>
    <?php endforeach; ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
