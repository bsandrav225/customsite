<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/site.php';

function order_redirect(string $error): void
{
    header('Location: order.php?error=' . rawurlencode($error));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: order.php');
    exit;
}

admin_require_post();

if (!rate_limit('order_submit:' . client_ip(), 6, 600)) {
    order_redirect('Слишком много заявок подряд. Попробуйте через 10 минут.');
}

require_csrf();
rate_limit_hit('order_submit:' . client_ip(), 600, 6);

$category = sanitize_text($_POST['category'] ?? '', 32);
$categoryOther = sanitize_text($_POST['category_other'] ?? '', 200);
$itemSource = sanitize_text($_POST['item_source'] ?? '', 16);
$transfer = sanitize_text($_POST['transfer'] ?? '', 16);
$size = sanitize_text($_POST['size'] ?? '', 32);
$sizeOther = sanitize_text($_POST['size_other'] ?? '', 64);
$sizeHelp = isset($_POST['size_help']);
$fit = sanitize_text($_POST['fit'] ?? '', 32);
$shoeModel = sanitize_text($_POST['shoe_model'] ?? '', 120);
$itemColor = sanitize_text($_POST['item_color'] ?? '', 64);
$itemNotes = sanitize_text($_POST['item_notes'] ?? '', 2000);
$idea = sanitize_text($_POST['idea'] ?? '', 5000);
$styles = $_POST['styles'] ?? [];
$palette = sanitize_text($_POST['palette'] ?? '', 64);
$placement = sanitize_text($_POST['placement'] ?? '', 64);
$budget = sanitize_text($_POST['budget'] ?? '', 64);
$deadlineType = sanitize_text($_POST['deadline_type'] ?? '', 32);
$deadlineDate = sanitize_text($_POST['deadline_date'] ?? '', 32);
$name = sanitize_text($_POST['name'] ?? '', 120);
$telegram = sanitize_text($_POST['telegram'] ?? '', 120);
$phone = sanitize_text($_POST['phone'] ?? '', 40);
$email = sanitize_text($_POST['email'] ?? '', 120);
$contactPref = sanitize_text($_POST['contact_pref'] ?? '', 32);
$reference = sanitize_text($_POST['reference'] ?? '', 64);
$consent = isset($_POST['consent']);

$orderCats = site_order_categories();
if (!isset($orderCats[$category])) {
    order_redirect('Выберите тип вещи.');
}
if ($category === 'other' && $categoryOther === '') {
    order_redirect('Опишите вещь в поле «Другое».');
}
if (!in_array($itemSource, ['own', 'buy'], true)) {
    order_redirect('Выберите, как будет предоставлена вещь.');
}
if ($itemSource === 'own' && !in_array($transfer, ['meetup', 'post', 'cdek'], true)) {
    order_redirect('Выберите способ передачи вещи.');
}
if ($idea === '') {
    order_redirect('Расскажите о вашей идее.');
}
if ($name === '') {
    order_redirect('Укажите имя.');
}
if ($telegram === '' && $phone === '' && $email === '') {
    order_redirect('Укажите хотя бы один способ связи.');
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    order_redirect('Укажите корректный email.');
}
if (!$consent) {
    order_redirect('Нужно согласие на обработку персональных данных.');
}

$styleList = [];
if (is_array($styles)) {
    foreach ($styles as $style) {
        $style = trim((string) $style);
        if ($style !== '') {
            $styleList[] = $style;
        }
    }
}

$uploadDir = __DIR__ . '/uploads/requests';
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
    order_redirect('Не удалось подготовить папку для файлов.');
}

$savedFiles = [];
if (!empty($_FILES['references']) && is_array($_FILES['references']['name'])) {
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    $maxFiles = 8;
    $maxBytes = 5 * 1024 * 1024;
    $count = min(count($_FILES['references']['name']), $maxFiles);

    for ($i = 0; $i < $count; $i++) {
        if ((int) $_FILES['references']['error'][$i] === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ((int) $_FILES['references']['error'][$i] !== UPLOAD_ERR_OK) {
            order_redirect('Не удалось загрузить один из файлов.');
        }
        if ((int) $_FILES['references']['size'][$i] > $maxBytes) {
            order_redirect('Файл больше 5 МБ. Уменьшите изображение.');
        }

        $tmp = (string) $_FILES['references']['tmp_name'][$i];
        if (!is_safe_uploaded_image($tmp)) {
            order_redirect('Можно прикладывать только изображения JPG, PNG, WEBP или GIF.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp) ?: '';
        if (!isset($allowed[$mime])) {
            order_redirect('Можно прикладывать только изображения JPG, PNG, WEBP или GIF.');
        }

        $basename = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $target = $uploadDir . '/' . $basename;
        if (!move_uploaded_file($tmp, $target)) {
            order_redirect('Не удалось сохранить файл.');
        }
        $savedFiles[] = 'uploads/requests/' . $basename;
    }
}

$labels = [
    'source' => ['own' => 'Своя вещь', 'buy' => 'Мастер покупает базу'],
    'transfer' => ['meetup' => 'Лично', 'post' => 'Почта', 'cdek' => 'СДЭК'],
    'deadline' => ['soon' => 'Как можно скорее', 'date' => 'Конкретная дата', 'flex' => 'Срок не важен'],
];

$details = [
    'category' => $category,
    'category_label' => $orderCats[$category],
    'category_other' => $categoryOther,
    'item_source' => $itemSource,
    'transfer' => $transfer,
    'size' => $size,
    'size_other' => $sizeOther,
    'size_help' => $sizeHelp,
    'fit' => $fit,
    'shoe_model' => $shoeModel,
    'item_color' => $itemColor,
    'item_notes' => $itemNotes,
    'idea' => $idea,
    'styles' => $styleList,
    'palette' => $palette,
    'placement' => $placement,
    'budget' => $budget,
    'deadline_type' => $deadlineType,
    'deadline_date' => $deadlineDate,
    'telegram' => $telegram,
    'contact_pref' => $contactPref,
    'reference' => $reference,
    'files' => $savedFiles,
];

$commentParts = [];
$commentParts[] = 'Идея: ' . $idea;
if ($reference !== '') {
    $refWork = site_find_work($reference);
    $commentParts[] = 'Референс: ' . ($refWork['title'] ?? $reference);
}
if ($itemSource === 'own') {
    $commentParts[] = 'Передача: ' . ($labels['transfer'][$transfer] ?? $transfer);
} else {
    $commentParts[] = 'Покупка базы. Размер: ' . ($sizeHelp ? 'нужна помощь' : ($size !== '' ? $size : 'не указан'));
    if ($itemColor !== '') {
        $commentParts[] = 'Цвет базы: ' . $itemColor;
    }
}
if ($telegram !== '') {
    $commentParts[] = 'Telegram: ' . $telegram;
}
$commentParts[] = 'JSON: ' . json_encode($details, JSON_UNESCAPED_UNICODE);

$type = $category;
$color = $itemColor !== '' ? $itemColor : 'unknown';
$sizeValue = $size !== '' ? $size : '-';
$text = function_exists('mb_substr') ? mb_substr($idea, 0, 250) : substr($idea, 0, 250);
$comment = implode("\n", $commentParts);

try {
    $pdo = getPDO();
    ensure_request_columns($pdo);

    $stmt = $pdo->prepare(
        'INSERT INTO request (`type`, color, size, text, name, tel, email, comment, telegram, details, files)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $type,
        $color,
        $sizeValue,
        $text,
        $name,
        $phone,
        $email,
        $comment,
        $telegram,
        json_encode($details, JSON_UNESCAPED_UNICODE),
        json_encode($savedFiles, JSON_UNESCAPED_UNICODE),
    ]);
} catch (PDOException $e) {
    try {
        $stmt = getPDO()->prepare(
            'INSERT INTO request (`type`, color, size, text, name, tel, email, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$type, $color, $sizeValue, $text, $name, $phone, $email, $comment]);
    } catch (PDOException $inner) {
        order_redirect('Ошибка сохранения заявки. Попробуйте ещё раз.');
    }
}

header('Location: order-success.php');
exit;

function ensure_request_columns(PDO $pdo): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $columns = [
        'telegram' => 'VARCHAR(120) NULL',
        'details' => 'TEXT NULL',
        'files' => 'TEXT NULL',
    ];

    foreach ($columns as $name => $definition) {
        try {
            $pdo->exec('ALTER TABLE request ADD COLUMN `' . $name . '` ' . $definition);
        } catch (PDOException $e) {
            // колонка уже есть
        }
    }
}
