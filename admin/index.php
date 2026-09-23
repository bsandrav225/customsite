<?php
declare(strict_types=1);
require_once __DIR__."/auth.php";
requireAdmin();
$pdo=getPDO();
$message="";

$productLabels=[
    'tshirt'=> 'футболка',
    'hoodie' => 'худи',
    'sweatshirt' => 'свитшот',
    'denim' => 'джинсовка',
    'jacket' => 'куртка',
    'shoes' => 'обувь',
    'bag' => 'сумка',
    'other' => 'другое',
    'poster' => 'постер',
    'gift' => 'подарок'
];

$productColors=[
    'white' => 'белый',
    'black' => 'чёрный',
    'gray' => 'серый',
    'beige' => 'бежевый',
    'colorful' => 'цветной',
    'master' => 'на усмотрение мастера',
    'unknown' => 'не указан',
    'swatch-1' => 'зелёный',
    'swatch-2' => 'темно-зелёный',
    'swatch-3' => 'фиолетовый',
    'swatch-4' => 'тёмно-фиолетовый',
    'swatch-5' => 'белый',
    'swatch-6' => 'чёрный',
    'swatch-7' => 'красный',
    'swatch-8' => 'оранжевый'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_require_post();
    require_csrf();

    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id > 0) {
        $stmt = $pdo->prepare('DELETE FROM request WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: index.php?deleted=' . $id);
        exit;
    }
}

if (isset($_GET['deleted'])) {
    $message = 'Заявка №' . (int) $_GET['deleted'] . ' удалена';
}

$requests = [];
try {
    $requests = $pdo->query("SELECT * FROM request ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    error_log('Admin requests query failed: ' . $e->getMessage());
    $message = 'Не удалось загрузить заявки. Проверьте, что база castomize импортирована.';
}

$viewId = isset($_GET['view']) ? (int) $_GET['view'] : 0;
$viewRequest = null;

if ($viewId > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM request WHERE id = ?");
        $stmt->execute([$viewId]);
        $viewRequest = $stmt->fetch() ?: null;
    } catch (PDOException $e) {
        $viewRequest = null;
    }
}

function productLabel(array $map, ?string $key, string $fallback = '-'): string {
    if($key === null || $key === ''){
        return $fallback;
    }
    return $map[$key] ?? $key;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки — CRAFTD Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="requests.css">
</head>
<body class="admin-body">
    <header class="admin-nav">
        <a href="../index.php" class="logo">CRAFT<span>D</span></a>
        <ul class="admin-nav-links">
            <li><a href="../index.php">Главная</a></li>
            <li><a href="../works.php">Работы</a></li>
            <li><a href="../order.php">Заявки с сайта</a></li>
            <li><a href="logout.php" class="nav-cta">Выход</a></li>
        </ul>
    </header>

    <main class="admin-main">
        <div class="admin-hero">
            <div class="section-tag">Админ-панель</div>
            <h1 class="admin-title">Заявки на <em>заказ</em></h1>
            <p class="admin-subtitle">Просматривайте детали и удаляйте обработанные заявки.</p>
            <p class="admin-meta">Всего заявок: <strong><?= count($requests) ?></strong></p>
        </div>

        <?php if($message !== ""): ?>
            <div class="form-alert form-alert-light admin-flash" role="status">
                <?= h($message) ?>
            </div>
        <?php endif; ?>

        <?php if($viewRequest): ?>
            <section class="admin-panel">
                <div class="admin-panel-header">
                    <h2 class="admin-panel-title">Заявка № <?= (int)$viewRequest['id'] ?></h2>
                    <a class="btn-ghost" href="index.php">Закрыть</a>
                </div>

                <dl class="request-detail">
                    <dt>Дата</dt>
                    <dd><?= h($viewRequest['created_at'] ?? '-') ?></dd>

                    <dt>Изделие</dt>
                    <dd><?= h(productLabel($productLabels, $viewRequest['type'] ?? null)) ?></dd>

                    <dt>Цвет</dt>
                    <dd><?= h(productLabel($productColors, $viewRequest['color'] ?? null)) ?></dd>

                    <dt>Размер</dt>
                    <dd><?= h($viewRequest['size'] ?? '-') ?></dd>

                    <dt>Текст</dt>
                    <dd><?= h(($viewRequest['text'] ?? '') !== '' ? $viewRequest['text'] : '—') ?></dd>

                    <dt>Имя</dt>
                    <dd><?= h($viewRequest['name'] ?? '-') ?></dd>

                    <dt>Телефон</dt>
                    <dd>
                        <?php if(!empty($viewRequest['tel'])): ?>
                            <a href="tel:<?= h($viewRequest['tel']) ?>"><?= h($viewRequest['tel']) ?></a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </dd>

                    <dt>E-mail</dt>
                    <dd>
                        <?php if(!empty($viewRequest['email'])): ?>
                            <a href="mailto:<?= h($viewRequest['email']) ?>"><?= h($viewRequest['email']) ?></a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </dd>

                    <dt>Telegram</dt>
                    <dd><?= h(($viewRequest['telegram'] ?? '') !== '' ? $viewRequest['telegram'] : '—') ?></dd>

                    <dt>Комментарий</dt>
                    <dd><?= h(($viewRequest['comment'] ?? '') !== '' ? $viewRequest['comment'] : '—') ?></dd>

                    <?php
                    $details = [];
                    if (!empty($viewRequest['details'])) {
                        $decoded = json_decode((string) $viewRequest['details'], true);
                        if (is_array($decoded)) {
                            $details = $decoded;
                        }
                    }
                    $files = [];
                    if (!empty($viewRequest['files'])) {
                        $decodedFiles = json_decode((string) $viewRequest['files'], true);
                        if (is_array($decodedFiles)) {
                            $files = $decodedFiles;
                        }
                    }
                    ?>

                    <?php if ($details): ?>
                    <dt>Детали заявки</dt>
                    <dd>
                        <ul class="request-details-list">
                            <?php foreach ($details as $key => $value): ?>
                                <?php if ($value === '' || $value === [] || $value === false) continue; ?>
                                <li><strong><?= h((string) $key) ?>:</strong> <?= h(is_array($value) ? implode(', ', $value) : (is_bool($value) ? 'да' : (string) $value)) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </dd>
                    <?php endif; ?>

                    <?php if ($files): ?>
                    <dt>Файлы</dt>
                    <dd>
                        <ul class="request-files">
                            <?php foreach ($files as $file): ?>
                                <li><a href="../<?= h((string) $file) ?>" target="_blank" rel="noopener">Открыть файл</a></li>
                            <?php endforeach; ?>
                        </ul>
                    </dd>
                    <?php endif; ?>
                </dl>

                <div class="request-detail-actions">
                    <form method="post" onsubmit="return confirm('Удалить заявку №<?= (int)$viewRequest['id'] ?> ?');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$viewRequest['id'] ?>">
                        <button type="submit" class="btn-danger">Удалить заявку</button>
                    </form>
                </div>
            </section>
        <?php else: ?>
            <section class="admin-panel">
                <?php if(!$requests): ?>
                    <p class="admin-empty">Пока нет ни одной заявки</p>
                <?php else: ?>
                    <div class="requests-table-wrap">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Дата</th>
                                    <th>Изделие</th>
                                    <th>Имя</th>
                                    <th>Телефон</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $r): ?>
                                    <tr>
                                        <td class="request-id"><?= (int)$r['id'] ?></td>
                                        <td><?= h($r['created_at'] ?? '-') ?></td>
                                        <td><?= h(productLabel($productLabels, $r['type'] ?? null)) ?></td>
                                        <td><?= h($r['name'] ?? '-') ?></td>
                                        <td>
                                            <?php if(!empty($r['tel'])): ?>
                                                <a href="tel:<?= h($r['tel']) ?>"><?= h($r['tel']) ?></a>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="request-actions">
                                                <a class="link-detail" href="index.php?view=<?= (int)$r['id'] ?>">Детали</a>
                                                <form method="post" onsubmit="return confirm('Удалить заявку №<?= (int)$r['id'] ?> ?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                                    <button type="submit" class="btn-danger">Удалить</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
