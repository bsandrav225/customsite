<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/site.php';

$preItem = trim((string) ($_GET['item'] ?? ''));
$preRef = trim((string) ($_GET['ref'] ?? ''));
$orderCats = site_order_categories();
if ($preItem !== '' && !isset($orderCats[$preItem])) {
    if ($preItem === 'other') {
        $preItem = 'other';
    } else {
        $map = ['sweatshirt' => 'sweatshirt'];
        $preItem = $map[$preItem] ?? (isset(site_categories()[$preItem]) ? $preItem : '');
    }
}

$refWork = $preRef !== '' ? site_find_work($preRef) : null;

$pageTitle = 'Заказать кастомизацию — CRAFTD';
$pageDescription = 'Заявка на ручную кастомизацию: выберите вещь, сценарий, опишите идею и приложите референсы.';
$current = 'order.php';
$bodyClass = 'constructor-page';
$formError = trim((string) ($_GET['error'] ?? ''));
$extraHead = <<<'HTML'
<noscript>
<style>
.order-step[hidden]{display:block!important}
#orderNext,#orderPrev{display:none!important}
#orderSubmit{display:inline-flex!important}
.order-progress{display:none}
</style>
</noscript>
HTML;

require __DIR__ . '/includes/header.php';

$styles = [
    'master' => 'На усмотрение мастера',
    'minimal' => 'Минимализм',
    'graphic' => 'Графика',
    'anime' => 'Аниме',
    'abstract' => 'Абстракция',
    'characters' => 'Персонажи',
    'lettering' => 'Надписи',
    'tattoo' => 'Тату-стиль',
    'retro' => 'Ретро',
    'other' => 'Другое',
];
$palettes = [
    'light' => 'Светлая',
    'dark' => 'Тёмная',
    'bright' => 'Яркая',
    'pastel' => 'Пастельная',
    'bw' => 'Чёрно-белая',
    'master' => 'На усмотрение мастера',
];
$placements = [
    'front' => 'Передняя часть',
    'back' => 'Спина',
    'sleeve' => 'Рукав',
    'multi' => 'Несколько зон',
    'master' => 'На усмотрение мастера',
];
$budgets = [
    'unknown' => 'Пока не знаю',
    'small' => 'Небольшой кастом',
    'medium' => 'Средний дизайн',
    'full' => 'Полная кастомизация',
];
$itemColors = [
    'white' => 'Белый',
    'black' => 'Чёрный',
    'gray' => 'Серый',
    'beige' => 'Бежевый',
    'colorful' => 'Цветной',
    'master' => 'На усмотрение мастера',
];
?>

<section class="constructor-hero">
  <div class="constructor-hero-inner">
    <div class="section-tag">Заявка</div>
    <h1 class="constructor-title">Заказать <em>кастомизацию</em></h1>
    <p class="constructor-subtitle">
      Пять коротких шагов. Показываю только те поля, которые нужны
      вашему сценарию — свою вещь или новую базу.
    </p>
  </div>
</section>

<section class="section order-section">
  <?php if ($formError !== ''): ?>
    <div class="form-alert form-alert-error" role="alert"><?= h($formError) ?></div>
  <?php endif; ?>

  <form class="order-form constructor-form glow-frame" id="orderForm" action="request.php" method="post" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <span class="form-alert-neon-dot" aria-hidden="true"></span>
    <input type="hidden" name="reference" value="<?= h($preRef) ?>">
    <input type="hidden" name="step_complete" value="1">

    <ol class="order-progress" aria-label="Шаги заявки">
      <li class="is-active" data-progress="1"><span>1</span> Вещь</li>
      <li data-progress="2"><span>2</span> Сценарий</li>
      <li data-progress="3"><span>3</span> Идея</li>
      <li data-progress="4"><span>4</span> Сроки</li>
      <li data-progress="5"><span>5</span> Контакты</li>
    </ol>

    <div class="order-step is-active" data-step="1">
      <h2>Что нужно кастомизировать?</h2>
      <p class="step-hint">Выберите тип вещи. Если своего варианта нет — «Другое».</p>
      <div class="choice-grid" role="radiogroup" aria-label="Тип вещи">
        <?php foreach ($orderCats as $key => $label): ?>
          <label class="choice-card">
            <input type="radio" name="category" value="<?= h($key) ?>"<?= $preItem === $key ? ' checked' : '' ?> required>
            <span class="choice-card-ui">
              <?= icon(['tshirt' => 'shirt', 'hoodie' => 'hoodie', 'sweatshirt' => 'hoodie', 'denim' => 'denim', 'jacket' => 'jacket', 'shoes' => 'shoe', 'bag' => 'bag', 'other' => 'other'][$key] ?? 'other', 26) ?>
              <span><?= h($label) ?></span>
            </span>
          </label>
        <?php endforeach; ?>
      </div>
      <div class="form-group is-conditional" data-show-when="category=other" hidden>
        <label class="form-label" for="category_other">Опишите вещь</label>
        <input class="form-input" type="text" id="category_other" name="category_other" placeholder="Например: джинсы, кепка, плащ">
      </div>
    </div>

    <div class="order-step" data-step="2" hidden>
      <h2>Как будет предоставлена вещь?</h2>
      <p class="step-hint">Два разных сценария. Заполнять нужно только свой.</p>
      <div class="scenario-grid">
        <label class="scenario-card">
          <input type="radio" name="item_source" value="own" required>
          <span class="scenario-card-ui">
            <?= icon('package', 32) ?>
            <strong>Я отправлю свою вещь мастеру</strong>
            <span>Уже есть футболка, худи, джинсовка или другая база.</span>
          </span>
        </label>
        <label class="scenario-card">
          <input type="radio" name="item_source" value="buy" required>
          <span class="scenario-card-ui">
            <?= icon('bag', 32) ?>
            <strong>Мастер подберёт и купит вещь</strong>
            <span>Нет подходящей базы — подберём размер, цвет и фасон.</span>
          </span>
        </label>
      </div>

      <div class="is-conditional" data-show-when="item_source=own" hidden>
        <h3 class="subhead">Способ передачи</h3>
        <div class="choice-grid choice-grid--3">
          <label class="choice-card">
            <input type="radio" name="transfer" value="meetup">
            <span class="choice-card-ui"><?= icon('pin', 22) ?><span>Лично</span></span>
          </label>
          <label class="choice-card">
            <input type="radio" name="transfer" value="post">
            <span class="choice-card-ui"><?= icon('mail', 22) ?><span>Почта</span></span>
          </label>
          <label class="choice-card">
            <input type="radio" name="transfer" value="cdek">
            <span class="choice-card-ui"><?= icon('truck', 22) ?><span>СДЭК</span></span>
          </label>
        </div>
        <div class="info-note" data-transfer-info="meetup" hidden>
          <p>После подтверждения заявки согласуем место и время. До получения вещи ничего отправлять не нужно.</p>
        </div>
        <div class="info-note" data-transfer-info="post" hidden>
          <p>Данные для отправки Почтой России придут после подтверждения заявки. Пересылку обычно оплачивает отправитель — уточним в переписке.</p>
        </div>
        <div class="info-note" data-transfer-info="cdek" hidden>
          <p>После подтверждения заявки вы получите данные для СДЭК и короткую инструкцию. Отправлять вещь до этого не нужно.</p>
        </div>
      </div>

      <div class="is-conditional buy-fields" data-show-when="item_source=buy" hidden>
        <h3 class="subhead">Параметры вещи для покупки</h3>
        <div class="form-row">
          <div class="form-group">
            <span class="form-label">Размер</span>
            <div class="size-options size-options-light">
              <?php foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Другой'] as $size): ?>
                <label class="size-opt-label">
                  <input type="radio" name="size" value="<?= h($size) ?>">
                  <span><?= h($size) ?></span>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <label class="check-line">
          <input type="checkbox" name="size_help" value="1" id="sizeHelp">
          <span>Не знаю точный размер, нужна помощь с выбором</span>
        </label>
        <div class="form-group is-conditional" data-show-when="size=Другой" hidden>
          <label class="form-label" for="size_other">Какой размер нужен</label>
          <input class="form-input" type="text" id="size_other" name="size_other">
        </div>
        <div class="form-group" data-for-types="tshirt hoodie sweatshirt denim jacket">
          <label class="form-label" for="fit">Посадка / стиль</label>
          <input class="form-input" type="text" id="fit" name="fit" placeholder="Оверсайз, классика, укороченный...">
        </div>
        <div class="form-group" data-for-types="shoes">
          <label class="form-label" for="shoe_model">Модель или тип обуви</label>
          <input class="form-input" type="text" id="shoe_model" name="shoe_model" placeholder="Кеды, сникеры, конкретная модель">
        </div>
        <div class="form-group">
          <span class="form-label">Цвет вещи</span>
          <div class="choice-grid choice-grid--3">
            <?php foreach ($itemColors as $key => $label): ?>
              <label class="choice-card choice-card--sm">
                <input type="radio" name="item_color" value="<?= h($key) ?>">
                <span class="choice-card-ui"><span><?= h($label) ?></span></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label" for="item_notes">Дополнительные пожелания к базе</label>
          <textarea class="form-input form-textarea" id="item_notes" name="item_notes" rows="3" placeholder="Плотность ткани, бренд, чего избегать..."></textarea>
        </div>
        <p class="info-note">Если размер неясен — обсудим после заявки. Покупать вещь до согласования не буду.</p>
      </div>
    </div>

    <div class="order-step" data-step="3" hidden>
      <h2>Идея кастомизации</h2>
      <?php if ($refWork): ?>
        <p class="ref-banner">
          Референс из портфолио: <strong><?= h($refWork['title']) ?></strong>.
          Это вдохновение для новой работы, а не заказ копии.
        </p>
      <?php endif; ?>
      <div class="form-group">
        <label class="form-label" for="idea">Расскажите о вашей идее <span class="req">обязательно</span></label>
        <textarea class="form-input form-textarea" id="idea" name="idea" rows="6" required placeholder="Чем подробнее вы опишете идею, тем проще будет понять, какой результат вы хотите получить."></textarea>
      </div>
      <fieldset class="form-group">
        <legend class="form-label">Стиль дизайна — можно несколько</legend>
        <div class="choice-grid choice-grid--3">
          <?php foreach ($styles as $key => $label): ?>
            <label class="choice-card choice-card--sm">
              <input type="checkbox" name="styles[]" value="<?= h($key) ?>">
              <span class="choice-card-ui"><span><?= h($label) ?></span></span>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <fieldset class="form-group">
        <legend class="form-label">Цветовая гамма</legend>
        <div class="choice-grid choice-grid--3">
          <?php foreach ($palettes as $key => $label): ?>
            <label class="choice-card choice-card--sm">
              <input type="radio" name="palette" value="<?= h($key) ?>">
              <span class="choice-card-ui"><span><?= h($label) ?></span></span>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <fieldset class="form-group">
        <legend class="form-label">Место расположения рисунка</legend>
        <div class="choice-grid choice-grid--3">
          <?php foreach ($placements as $key => $label): ?>
            <label class="choice-card choice-card--sm">
              <input type="radio" name="placement" value="<?= h($key) ?>">
              <span class="choice-card-ui"><span><?= h($label) ?></span></span>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <div class="form-group">
        <span class="form-label" id="filesLabel">Референсы</span>
        <p class="step-hint">Добавьте референсы или примеры того, что вам нравится. Можно фото своей вещи.</p>
        <div class="dropzone" id="dropzone">
          <input type="file" name="references[]" id="fileInput" accept="image/jpeg,image/png,image/webp,image/gif" multiple aria-labelledby="filesLabel">
          <div class="dropzone-ui">
            <?= icon('upload', 28) ?>
            <strong>Перетащите файлы сюда</strong>
            <span>или нажмите, чтобы выбрать. До 8 изображений.</span>
          </div>
        </div>
        <ul class="file-previews" id="filePreviews"></ul>
      </div>
    </div>

    <div class="order-step" data-step="4" hidden>
      <h2>Бюджет и сроки</h2>
      <p class="step-hint">Необязательно, но помогает понять масштаб.</p>
      <fieldset class="form-group">
        <legend class="form-label">Ориентировочный бюджет</legend>
        <div class="choice-grid">
          <?php foreach ($budgets as $key => $label): ?>
            <label class="choice-card">
              <input type="radio" name="budget" value="<?= h($key) ?>">
              <span class="choice-card-ui"><span><?= h($label) ?></span></span>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <fieldset class="form-group">
        <legend class="form-label">Когда нужна вещь?</legend>
        <div class="choice-grid">
          <label class="choice-card">
            <input type="radio" name="deadline_type" value="soon">
            <span class="choice-card-ui"><?= icon('clock', 22) ?><span>Как можно скорее</span></span>
          </label>
          <label class="choice-card">
            <input type="radio" name="deadline_type" value="date">
            <span class="choice-card-ui"><?= icon('calendar', 22) ?><span>Есть конкретная дата</span></span>
          </label>
          <label class="choice-card">
            <input type="radio" name="deadline_type" value="flex">
            <span class="choice-card-ui"><?= icon('check', 22) ?><span>Срок не важен</span></span>
          </label>
        </div>
      </fieldset>
      <div class="form-group is-conditional" data-show-when="deadline_type=date" hidden>
        <label class="form-label" for="deadline_date">Дата</label>
        <input class="form-input" type="date" id="deadline_date" name="deadline_date">
      </div>
    </div>

    <div class="order-step" data-step="5" hidden>
      <h2>Контактные данные</h2>
      <p class="step-hint">Имя обязательно. Достаточно одного способа связи.</p>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="name">Имя <span class="req">обязательно</span></label>
          <input class="form-input" type="text" id="name" name="name" required autocomplete="name">
        </div>
        <div class="form-group">
          <label class="form-label" for="telegram">Telegram</label>
          <input class="form-input" type="text" id="telegram" name="telegram" placeholder="@username" autocomplete="username">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label" for="phone">Телефон</label>
          <input class="form-input" type="tel" id="phone" name="phone" placeholder="+7" autocomplete="tel">
        </div>
        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input class="form-input" type="email" id="email" name="email" placeholder="you@mail.ru" autocomplete="email">
        </div>
      </div>
      <fieldset class="form-group">
        <legend class="form-label">Предпочтительный способ связи</legend>
        <div class="choice-grid choice-grid--3">
          <label class="choice-card choice-card--sm">
            <input type="radio" name="contact_pref" value="telegram">
            <span class="choice-card-ui"><?= icon('telegram', 18) ?><span>Telegram</span></span>
          </label>
          <label class="choice-card choice-card--sm">
            <input type="radio" name="contact_pref" value="phone">
            <span class="choice-card-ui"><?= icon('phone', 18) ?><span>Телефон</span></span>
          </label>
          <label class="choice-card choice-card--sm">
            <input type="radio" name="contact_pref" value="email">
            <span class="choice-card-ui"><?= icon('mail', 18) ?><span>Email</span></span>
          </label>
        </div>
      </fieldset>
      <label class="check-line">
        <input type="checkbox" name="consent" value="1" required>
        <span>Соглашаюсь на <a href="consent.php" target="_blank" rel="noopener">обработку персональных данных</a> и принимаю <a href="privacy.php" target="_blank" rel="noopener">политику конфиденциальности</a></span>
      </label>
    </div>

    <div class="order-nav">
      <button type="button" class="btn-ghost" id="orderPrev" hidden>Назад</button>
      <button type="button" class="btn-primary" id="orderNext"><span>Далее</span></button>
      <button type="submit" class="btn-primary" id="orderSubmit" hidden><span>Отправить заявку</span></button>
    </div>
  </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
