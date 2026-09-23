/* ============================================================
   CRAFTD — main.js
   Весь интерактивный JavaScript сайта.

   ОГЛАВЛЕНИЕ:
   1. Кастомный курсор        — точка следует за мышью, кольцо с инерцией
   2. Hover-эффект курсора    — курсор меняется при наведении на кнопки/карточки
   3. Scroll-reveal           — плавное появление блоков при прокрутке
   4. Выбор размера           — переключение активного размера в конструкторе
   5. Выбор цвета (свотч)     — переключение активного свотча в конструкторе
   ============================================================ */


/* ─────────────────────────────────────────────────────────────
   1. КАСТОМНЫЙ КУРСОР
   Элементы:
     .cursor     — маленькая точка, перемещается мгновенно вслед за мышью.
     .cursor-ring — большое кольцо, перемещается с инерцией (плавный lag).

   Принцип работы точки:
     Слушатель mousemove сохраняет координаты мыши (mx, my) и
     тут же присваивает их точке через style.left / style.top.

   Принцип работы кольца (анимационный цикл requestAnimationFrame):
     В каждом кадре текущая позиция кольца (rx, ry) приближается
     к позиции мыши на 12% — создаётся эффект упругого следования.
   ───────────────────────────────────────────────────────────── */

/** @type {HTMLElement} Маленькая точка курсора */
const cursor = document.getElementById('cursor');

/** @type {HTMLElement} Большое кольцо курсора */
const ring = document.getElementById('cursorRing');

/** @type {number} Текущая X-позиция мыши */
let mx = 0;
/** @type {number} Текущая Y-позиция мыши */
let my = 0;
/** @type {number} Текущая X-позиция кольца (с инерцией) */
let rx = 0;
/** @type {number} Текущая Y-позиция кольца (с инерцией) */
let ry = 0;

/* Обновляем позицию точки мгновенно при движении мыши */
document.addEventListener('mousemove', (e) => {
  mx = e.clientX;
  my = e.clientY;

  if (cursor) {
    cursor.style.left = mx + 'px';
    cursor.style.top  = my + 'px';
  }
});

/**
 * Анимационный цикл кольца.
 * Каждый кадр кольцо приближается к курсору на 12% от разницы — «инерция».
 * requestAnimationFrame обеспечивает плавную работу (60fps).
 */
function animateRing() {
  if (!ring) return;

  rx += (mx - rx) * 0.12; /* плавное приближение по X */
  ry += (my - ry) * 0.12; /* плавное приближение по Y */

  ring.style.left = rx + 'px';
  ring.style.top  = ry + 'px';

  requestAnimationFrame(animateRing);
}

/* Запускаем цикл при загрузке страницы */
if (ring) animateRing();


/* ─────────────────────────────────────────────────────────────
   2. HOVER-ЭФФЕКТ КУРСОРА
   При наведении на интерактивные элементы (ссылки, кнопки, карточки,
   свотчи и т.д.) курсор и кольцо визуально «реагируют»:

   mouseenter — курсор увеличивается и становится фиолетовым,
                кольцо расширяется и зеленеет.
   mouseleave — всё возвращается к исходному виду.

   Список отслеживаемых элементов:
     a, button          — все ссылки и кнопки
     .swatch            — цветовые свотчи в конструкторе
     .size-opt          — кнопки выбора размера
     .cat-card          — карточки категорий
     .product-card      — карточки товаров
     .social-btn        — кнопки соцсетей в футере
     .add-btn           — кнопка «+» добавления в корзину
   ───────────────────────────────────────────────────────────── */

/* Собираем все интерактивные элементы в один NodeList */
const interactiveElements = document.querySelectorAll(
  'a, button, summary, .swatch, .size-opt, .cat-card, .product-card, .work-card, .social-btn, .add-btn, .nav-toggle, .form-input, .form-select, .custom-select-trigger, .custom-select-option, .color-picker-close, .color-picker-apply, #colorWheel, .choice-card, .scenario-card, .chip, .dropzone, .size-opt-label'
);

interactiveElements.forEach((el) => {
  if (!cursor || !ring) return;

  el.addEventListener('mouseenter', () => {
    cursor.style.transform    = 'translate(-50%, -50%) scale(2.5)';
    cursor.style.background   = 'var(--purple-mid)';

    ring.style.width          = '56px';
    ring.style.height         = '56px';
    ring.style.borderColor    = 'var(--green-bright)';
  });

  el.addEventListener('mouseleave', () => {
    cursor.style.transform    = 'translate(-50%, -50%) scale(1)';
    cursor.style.background   = 'var(--green-mid)';

    ring.style.width          = '36px';
    ring.style.height         = '36px';
    ring.style.borderColor    = 'var(--purple-mid)';
  });
});


/* ─────────────────────────────────────────────────────────────
   3. SCROLL-REVEAL (плавное появление при прокрутке)
   Все элементы с классом .reveal изначально невидимы и чуть сдвинуты вниз
   (задаётся через CSS: opacity:0, transform:translateY(30px)).

   IntersectionObserver наблюдает за каждым .reveal элементом.
   Когда элемент попадает в зону видимости (threshold: 10%),
   ему добавляется класс .visible — и CSS-переход плавно его показывает.

   Небольшая задержка (i * 60ms) создаёт эффект каскадного появления:
   несколько соседних элементов появляются поочерёдно, а не одновременно.

   После срабатывания observer.unobserve() отключает слежение —
   анимация воспроизводится один раз, не повторяется.
   ───────────────────────────────────────────────────────────── */

/** @type {NodeList} Все элементы с классом .reveal */
const revealElements = document.querySelectorAll('.reveal');

/**
 * IntersectionObserver — отслеживает пересечение элементов с viewport.
 * @param {IntersectionObserverEntry[]} entries — массив наблюдаемых элементов
 */
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry, i) => {
    if (entry.isIntersecting) {
      /* Каскадная задержка: каждый следующий элемент появляется на 60мс позже */
      setTimeout(() => {
        entry.target.classList.add('visible');
      }, i * 60);

      /* Перестаём следить — анимация не повторяется */
      revealObserver.unobserve(entry.target);
    }
  });
}, {
  threshold: 0.1 /* элемент должен быть виден хотя бы на 10% */
});

/* Подключаем observer ко всем .reveal-элементам */
revealElements.forEach((el) => revealObserver.observe(el));


/* ─────────────────────────────────────────────────────────────
   4. ВЫБОР РАЗМЕРА (конструктор кастомизации)
   Функция selectSize() вызывается через onclick в HTML.
   Снимает класс .active со всех кнопок размера,
   затем добавляет его только кликнутой кнопке.
   Визуальная смена активного состояния — через CSS (.size-opt.active).
   ───────────────────────────────────────────────────────────── */

/**
 * Активирует кнопку выбора размера.
 * @param {HTMLElement} el — кликнутая кнопка размера
 */
function selectSize(el) {
  document.querySelectorAll('.size-opt').forEach((s) => s.classList.remove('active'));
  el.classList.add('active');

  const sizeInput = document.getElementById('sizeInput');
  if (sizeInput) {
    sizeInput.value = el.textContent.trim();
  }
}

/* Делаем функцию глобальной (вызывается из onclick в HTML) */
window.selectSize = selectSize;


/* ─────────────────────────────────────────────────────────────
   5. ВЫБОР ЦВЕТА (свотч, конструктор кастомизации)
   Аналогично selectSize(), но для цветовых кружков.
   Функция selectSwatch() вызывается через onclick в HTML.
   Снимает класс .active со всех свотчей,
   затем ставит его на кликнутый.
   Активный свотч выделяется белой рамкой (CSS: .swatch.active).
   ───────────────────────────────────────────────────────────── */

/**
 * Активирует цветовой свотч.
 * @param {HTMLElement} el — кликнутый свотч
 */
function selectSwatch(el) {
  document.querySelectorAll('.swatch:not(.swatch-custom)').forEach((s) => s.classList.remove('active'));

  const customBtn = document.getElementById('customColorBtn');
  if (customBtn) {
    customBtn.classList.remove('active');
  }

  el.classList.add('active');

  const colorInput = document.getElementById('colorInput');
  if (colorInput) {
    colorInput.value = el.dataset.color || 'swatch-1';
  }
}

/* Делаем функцию глобальной (вызывается из onclick в HTML) */
window.selectSwatch = selectSwatch;


/* ─────────────────────────────────────────────────────────────
   5b. КАСТОМНЫЙ ВЫПАДАЮЩИЙ СПИСОК (конструктор)
   Заменяет нативный select: кастомный курсор и цвета бренда.
   ───────────────────────────────────────────────────────────── */

function initCustomSelect() {
  const selectRoot = document.getElementById('productSelect');
  if (!selectRoot) return;

  const trigger = selectRoot.querySelector('.custom-select-trigger');
  const valueEl = selectRoot.querySelector('.custom-select-value');
  const optionsList = selectRoot.querySelector('.custom-select-options');
  const hiddenInput = selectRoot.querySelector('input[type="hidden"]');
  const options = selectRoot.querySelectorAll('.custom-select-option');

  function closeSelect() {
    selectRoot.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');
  }

  function openSelect() {
    selectRoot.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
    const selected = selectRoot.querySelector('.custom-select-option.selected');
    if (selected) selected.focus();
    else optionsList.focus();
  }

  function chooseOption(option) {
    options.forEach((item) => {
      const isSelected = item === option;
      item.classList.toggle('selected', isSelected);
      item.setAttribute('aria-selected', String(isSelected));
    });

    hiddenInput.value = option.dataset.value;
    valueEl.textContent = option.textContent.trim();
    closeSelect();
    trigger.focus();
  }

  trigger.addEventListener('click', () => {
    if (selectRoot.classList.contains('open')) {
      closeSelect();
    } else {
      openSelect();
    }
  });

  options.forEach((option) => {
    option.setAttribute('tabindex', '-1');

    option.addEventListener('click', () => chooseOption(option));

    option.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        chooseOption(option);
      }
    });
  });

  optionsList.addEventListener('keydown', (e) => {
    const currentIndex = Array.from(options).findIndex((item) => item.classList.contains('selected'));
    let nextIndex = currentIndex;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      nextIndex = (currentIndex + 1) % options.length;
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      nextIndex = (currentIndex - 1 + options.length) % options.length;
    } else if (e.key === 'Escape') {
      closeSelect();
      trigger.focus();
      return;
    } else {
      return;
    }

    options[nextIndex].focus();
  });

  document.addEventListener('click', (e) => {
    if (!selectRoot.contains(e.target)) {
      closeSelect();
    }
  });
}

initCustomSelect();


/* ─────────────────────────────────────────────────────────────
   5c. RGB-КРУГ (пользовательский цвет)
   ───────────────────────────────────────────────────────────── */

const colorPickerState = {
  hue: 150,
  lightness: 50,
  hex: '#52b788',
};

function hslToHex(h, s, l) {
  s /= 100;
  l /= 100;

  const c = (1 - Math.abs(2 * l - 1)) * s;
  const x = c * (1 - Math.abs(((h / 60) % 2) - 1));
  const m = l - c / 2;
  let r = 0;
  let g = 0;
  let b = 0;

  if (h < 60) {
    r = c; g = x; b = 0;
  } else if (h < 120) {
    r = x; g = c; b = 0;
  } else if (h < 180) {
    r = 0; g = c; b = x;
  } else if (h < 240) {
    r = 0; g = x; b = c;
  } else if (h < 300) {
    r = x; g = 0; b = c;
  } else {
    r = c; g = 0; b = x;
  }

  const toHex = (value) => Math.round((value + m) * 255).toString(16).padStart(2, '0');
  return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
}

function updateColorPreview() {
  const hexLabel = document.getElementById('colorHexLabel');
  const swatch = document.getElementById('colorPickerSwatch');
  colorPickerState.hex = hslToHex(colorPickerState.hue, 100, colorPickerState.lightness);
  
  if (hexLabel) hexLabel.textContent = colorPickerState.hex;
  if (swatch) swatch.style.background = colorPickerState.hex;
}

function updateWheelMarker() {
  // const marker = document.getElementById('colorWheelMarker');
  // const canvas = document.getElementById('colorWheel');
  // if (!marker || !canvas) return;

  // const cx = canvas.width / 2;
  // const cy = canvas.height / 2;
  // const outerR = cx - 4;
  // const innerR = outerR * 0.58;
  // const midR = (outerR + innerR) / 2;
  // const angleRad = colorPickerState.hue * (Math.PI / 180);

  // marker.style.transform = `translate(${cx + Math.cos(angleRad) * midR}px, ${cy + Math.sin(angleRad) * midR}px)`;
  // marker.style.background = `hsl(${colorPickerState.hue}, 100%, ${colorPickerState.lightness}%)`;
  const marker = document.getElementById('colorWheelMarker');
  const canvas = document.getElementById('colorWheel');
  if (!marker || !canvas) return;

  const cx = canvas.width / 2;
  const cy = canvas.height / 2;
  const outerR = cx - 4;
  const innerR = outerR * 0.58;
  const midR = (outerR + innerR) / 2;
  const angleRad = (colorPickerState.hue) * (Math.PI / 180);

  const x = cx + Math.cos(angleRad) * midR;
  const y = cy + Math.sin(angleRad) * midR;

  /* Position marker center at (x, y) by offsetting by half marker size (9px) */
  marker.style.left = (x - 9) + 'px';
  marker.style.top = (y - 9) + 'px';
  marker.style.background = `hsl(${colorPickerState.hue}, 100%, ${colorPickerState.lightness}%)`;
}


function drawColorWheel() {
  const canvas = document.getElementById('colorWheel');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  const cx = canvas.width / 2;
  const cy = canvas.height / 2;
  const outerR = cx - 4;
  const innerR = outerR * 0.58;

  ctx.clearRect(0, 0, canvas.width, canvas.height);

  for (let angle = 0; angle < 360; angle += 1) {
    const start = ((angle - 0.6) * Math.PI) / 180;
    const end = ((angle + 0.6) * Math.PI) / 180;

    ctx.beginPath();
    ctx.arc(cx, cy, outerR, start, end);
    ctx.arc(cx, cy, innerR, end, start, true);
    ctx.closePath();
    ctx.fillStyle = `hsl(${angle}, 100%, 50%)`;
    ctx.fill();
  }

  ctx.beginPath();
  ctx.arc(cx, cy, innerR - 1, 0, Math.PI * 2);
  ctx.fillStyle = 'rgba(26, 58, 42, 0.92)';
  ctx.fill();
}

function pickHueFromWheel(clientX, clientY) {
  const canvas = document.getElementById('colorWheel');
  if (!canvas) return;

  const rect = canvas.getBoundingClientRect();
  const x = clientX - rect.left - rect.width / 2;
  const y = clientY - rect.top - rect.height / 2;
  const outerR = rect.width / 2 - 4;
  const innerR = outerR * 0.58;
  const distance = Math.sqrt(x * x + y * y);

  if (distance < innerR || distance > outerR) return;

  let hue = (Math.atan2(y, x) * 180) / Math.PI;
  if (hue < 0) hue += 360;

  colorPickerState.hue = Math.round(hue);
  updateColorPreview();
  updateWheelMarker();
}

function openColorPicker(event) {
  event.preventDefault();
  event.stopPropagation();

  const modal = document.getElementById('colorPickerModal');
  if (!modal) return;

  drawColorWheel();
  updateColorPreview();
  updateWheelMarker();

  modal.hidden = false;
  modal.setAttribute('aria-hidden', 'false');
  document.body.classList.add('color-picker-open');
}

function closeColorPicker() {
  const modal = document.getElementById('colorPickerModal');
  if (!modal) return;

  modal.hidden = true;
  modal.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('color-picker-open');
}

// function applyCustomColor() {
//   const customBtn = document.getElementById('customColorBtn');
//   const customFill = document.getElementById('customColorFill');
//   const colorInput = document.getElementById('colorInput');

//   document.querySelectorAll('.swatch:not(.swatch-custom)').forEach((s) => s.classList.remove('active'));

//   if (customBtn) {
//     customBtn.classList.add('active', 'has-color');
//     customBtn.style.setProperty('--custom-swatch-color', colorPickerState.hex);
//   }

//   if (customFill) {
//     customFill.style.background = colorPickerState.hex;
//   }

//   if (colorInput) {
//     colorInput.value = colorPickerState.hex;
//   }

//   closeColorPicker();
// }

function applyCustomColor() {
  const customBtn = document.getElementById('customColorBtn');
  const customFill = document.getElementById('customColorFill');
  const colorInput = document.getElementById('colorInput');
  const swatch = document.getElementById('colorPickerSwatch');

  document.querySelectorAll('.swatch:not(.swatch-custom)').forEach((s) => s.classList.remove('active'));

  if (customBtn) {
    customBtn.classList.add('active', 'has-color');
    customBtn.style.setProperty('--custom-swatch-color', colorPickerState.hex);
  }

  if (customFill) {
    customFill.style.background = colorPickerState.hex;
  }

  if (swatch) {
    swatch.style.background = colorPickerState.hex;
  }

  if (colorInput) {
    colorInput.value = colorPickerState.hex;
  }

  closeColorPicker();
}

function initColorPicker() {
  const modal = document.getElementById('colorPickerModal');
  const canvas = document.getElementById('colorWheel');
  const brightness = document.getElementById('colorBrightness');
  const applyBtn = document.getElementById('colorPickerApply');
  const closeBtn = document.getElementById('colorPickerClose');

  if (!modal || !canvas) return;

  let dragging = false;

  canvas.addEventListener('mousedown', (e) => {
    dragging = true;
    pickHueFromWheel(e.clientX, e.clientY);
  });

  window.addEventListener('mousemove', (e) => {
    if (dragging) pickHueFromWheel(e.clientX, e.clientY);
  });

  window.addEventListener('mouseup', () => {
    dragging = false;
  });

  canvas.addEventListener('click', (e) => pickHueFromWheel(e.clientX, e.clientY));

  canvas.addEventListener('touchstart', (e) => {
    dragging = true;
    const touch = e.touches[0];
    if (touch) pickHueFromWheel(touch.clientX, touch.clientY);
  }, { passive: true });

  canvas.addEventListener('touchmove', (e) => {
    if (!dragging) return;
    const touch = e.touches[0];
    if (touch) pickHueFromWheel(touch.clientX, touch.clientY);
  }, { passive: true });

  canvas.addEventListener('touchend', () => {
    dragging = false;
  });

  if (brightness) {
    brightness.addEventListener('input', () => {
      colorPickerState.lightness = Number(brightness.value);
      updateColorPreview();
      updateWheelMarker();
    });
  }

  if (applyBtn) applyBtn.addEventListener('click', applyCustomColor);
  if (closeBtn) closeBtn.addEventListener('click', closeColorPicker);

  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeColorPicker();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.hidden) {
      closeColorPicker();
    }
  });
}

initColorPicker();
window.openColorPicker = openColorPicker;


/* ─────────────────────────────────────────────────────────────
   6. МОБИЛЬНОЕ МЕНЮ
   Бургер-кнопка открывает боковую панель навигации.
   Закрытие: повторный клик, клик по оверлею, клик по ссылке, Escape.
   ───────────────────────────────────────────────────────────── */

const navToggle = document.querySelector('.nav-toggle');
const navOverlay = document.getElementById('navOverlay');
const navLinks = document.querySelectorAll('.nav-links a');

function setNavOpen(isOpen) {
  document.body.classList.toggle('nav-open', isOpen);

  if (navToggle) {
    navToggle.setAttribute('aria-expanded', String(isOpen));
    navToggle.setAttribute('aria-label', isOpen ? 'Закрыть меню' : 'Открыть меню');
  }

  if (navOverlay) {
    navOverlay.setAttribute('aria-hidden', String(!isOpen));
  }
}

if (navToggle) {
  navToggle.addEventListener('click', () => {
    setNavOpen(!document.body.classList.contains('nav-open'));
  });
}

if (navOverlay) {
  navOverlay.addEventListener('click', () => setNavOpen(false));
}

navLinks.forEach((link) => {
  link.addEventListener('click', () => setNavOpen(false));
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    setNavOpen(false);
  }
});

window.addEventListener('resize', () => {
  if (window.innerWidth > 1180) {
    setNavOpen(false);
  }
});

/* ─────────────────────────────────────────────────────────────
   7. МНОГОШАГОВАЯ ЗАЯВКА
   ───────────────────────────────────────────────────────────── */

function initOrderForm() {
  const form = document.getElementById('orderForm');
  if (!form) return;

  const total = 5;
  let step = 1;
  const prevBtn = document.getElementById('orderPrev');
  const nextBtn = document.getElementById('orderNext');
  const submitBtn = document.getElementById('orderSubmit');
  const fileInput = document.getElementById('fileInput');
  const dropzone = document.getElementById('dropzone');
  const previews = document.getElementById('filePreviews');
  const fileStore = new DataTransfer();

  function showAlert(message) {
    let box = form.querySelector('.order-step-error');
    if (!box) {
      box = document.createElement('p');
      box.className = 'order-step-error';
      box.setAttribute('role', 'alert');
      form.querySelector('.order-nav').before(box);
    }
    box.textContent = message;
    box.hidden = !message;
  }

  function showStep(n) {
    step = n;
    form.querySelectorAll('.order-step').forEach((el) => {
      const current = Number(el.dataset.step) === n;
      el.hidden = !current;
      el.classList.toggle('is-active', current);
    });
    form.querySelectorAll('.order-progress li').forEach((el) => {
      const index = Number(el.dataset.progress);
      el.classList.toggle('is-active', index === n);
      el.classList.toggle('is-done', index < n);
    });
    prevBtn.hidden = n === 1;
    nextBtn.hidden = n === total;
    submitBtn.hidden = n !== total;
    showAlert('');
    window.scrollTo({ top: form.offsetTop - 90, behavior: 'smooth' });
  }

  function validateStep(n) {
    if (n === 1) {
      const cat = form.querySelector('input[name="category"]:checked');
      if (!cat) return 'Выберите тип вещи';
      if (cat.value === 'other' && !form.category_other.value.trim()) {
        return 'Опишите вещь';
      }
    }
    if (n === 2) {
      const src = form.querySelector('input[name="item_source"]:checked');
      if (!src) return 'Выберите, как будет предоставлена вещь';
      if (src.value === 'own' && !form.querySelector('input[name="transfer"]:checked')) {
        return 'Выберите способ передачи';
      }
    }
    if (n === 3 && !form.idea.value.trim()) {
      return 'Расскажите о вашей идее';
    }
    if (n === 5) {
      if (!form.name.value.trim()) return 'Укажите имя';
      if (!form.telegram.value.trim() && !form.phone.value.trim() && !form.email.value.trim()) {
        return 'Укажите хотя бы один способ связи';
      }
      if (!form.consent.checked) return 'Нужно согласие на обработку персональных данных';
    }
    return '';
  }

  function syncConditionals() {
    form.querySelectorAll('[data-show-when]').forEach((el) => {
      const [name, value] = el.dataset.showWhen.split('=');
      const checked = form.querySelector('[name="' + name + '"]:checked');
      el.hidden = !(checked && checked.value === value);
    });

    const transfer = form.querySelector('input[name="transfer"]:checked');
    form.querySelectorAll('[data-transfer-info]').forEach((el) => {
      el.hidden = !transfer || el.dataset.transferInfo !== transfer.value;
    });

    const cat = form.querySelector('input[name="category"]:checked');
    form.querySelectorAll('[data-for-types]').forEach((el) => {
      const types = el.dataset.forTypes.split(/\s+/);
      el.hidden = !cat || !types.includes(cat.value);
    });
  }

  function renderFiles() {
    if (!previews || !fileInput) return;
    previews.innerHTML = '';
    Array.from(fileStore.files).forEach((file, index) => {
      const li = document.createElement('li');
      li.className = 'file-preview';
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.alt = file.name;
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'file-remove';
      btn.setAttribute('aria-label', 'Удалить ' + file.name);
      btn.innerHTML = '&times;';
      btn.addEventListener('click', () => {
        const next = new DataTransfer();
        Array.from(fileStore.files).forEach((item, i) => {
          if (i !== index) next.items.add(item);
        });
        while (fileStore.files.length) fileStore.items.remove(0);
        Array.from(next.files).forEach((item) => fileStore.items.add(item));
        fileInput.files = fileStore.files;
        renderFiles();
      });
      li.append(img, btn);
      previews.append(li);
    });
    fileInput.files = fileStore.files;
  }

  function addFiles(list) {
    Array.from(list).forEach((file) => {
      if (!file.type.startsWith('image/')) return;
      if (fileStore.files.length >= 8) return;
      fileStore.items.add(file);
    });
    renderFiles();
  }

  form.addEventListener('change', syncConditionals);
  syncConditionals();
  showStep(1);

  nextBtn.addEventListener('click', () => {
    const error = validateStep(step);
    if (error) {
      showAlert(error);
      return;
    }
    if (step < total) showStep(step + 1);
  });

  prevBtn.addEventListener('click', () => {
    if (step > 1) showStep(step - 1);
  });

  form.addEventListener('submit', (e) => {
    const error = validateStep(5);
    if (error) {
      e.preventDefault();
      showAlert(error);
      showStep(5);
    }
  });

  if (dropzone && fileInput) {
    dropzone.addEventListener('click', (e) => {
      if (e.target !== fileInput) fileInput.click();
    });
    dropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropzone.classList.add('is-drag');
    });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('is-drag'));
    dropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropzone.classList.remove('is-drag');
      addFiles(e.dataTransfer.files);
    });
    fileInput.addEventListener('change', () => addFiles(fileInput.files));
  }
}

initOrderForm();