<?php require_once 'config.php'; requireAuth();
$db = getDb();
$user_id = $_SESSION['user_id'];
$user = $db->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$user_id]);
$userData = $user->fetch(PDO::FETCH_ASSOC);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    $item_key = $_POST['item_key'];
    if (isset($donate_items[$item_key])) {
        $item = $donate_items[$item_key];
        if ((int)$userData['points'] >= $item['cost']) {
            $newPoints = (int)$userData['points'] - $item['cost'];
            $db->prepare("UPDATE users SET points = ? WHERE id = ?")->execute([$newPoints, $user_id]);
            $db->prepare("INSERT INTO donations (user_id, item_key, item_name, cost, command, minecraft_nick) VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([$user_id, $item_key, $item['name'], $item['cost'], $item['command'], $userData['minecraft_nick']]);
            $userData['points'] = $newPoints;
            $success = '✅ Покупка оформлена! Ожидайте выдачи на сервере.';
        } else {
            $error = '❌ Недостаточно очков! Нужно ' . $item['cost'];
        }
    }
}

$items_by_cat = [];
foreach ($donate_items as $key => $item) {
    $cat = $item['category'] ?? 'other';
    $items_by_cat[$cat][$key] = $item;
}

$category_icons = [];
foreach ($shop_categories as $k => $c) {
    $category_icons[$k] = $c['icon'];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Магазин доната</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <a href="profile.php" class="btn btn-sm btn-outline">Профиль</a>
            <a href="snake.php" class="btn btn-sm btn-outline">Змейка</a>
            <a href="tetris.php" class="btn btn-sm btn-outline">Тетрис</a>
            <a href="leaderboard.php" class="btn btn-sm btn-outline">Лидеры</a>
            <a href="index.php" class="btn btn-sm btn-outline">Главная</a>
        </nav>
    </div>
</header>
<div class="container animate-in">
    <h1>🛒 Магазин доната</h1>

    <div class="shop-header">
        <div class="balance-badge">
            <span class="icon">⭐</span> <?= (int)$userData['points'] ?> очков
        </div>
        <div>
            <a href="snake.php" class="btn btn-sm btn-outline">🐍 Змейка</a>
            <a href="tetris.php" class="btn btn-sm btn-outline">🧊 Тетрис</a>
            <a href="wheel.php" class="btn btn-sm btn-outline">🎡 Колесо</a>
            <a href="scratch.php" class="btn btn-sm btn-outline">🎰 Скретч</a>
        </div>
    </div>

    <?php if ($success): ?><div class="msg msg-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg msg-error"><?= $error ?></div><?php endif; ?>

    <div class="shop-layout">
        <aside class="shop-sidebar">
            <div class="shop-categories" id="categories">
                <button class="cat-btn active" data-cat="all"><span class="icon">📋</span> Все</button>
                <?php foreach ($shop_categories as $key => $cat): ?>
                <button class="cat-btn" data-cat="<?= $key ?>">
                    <span class="icon"><?= $cat['icon'] ?></span> <?= $cat['name'] ?>
                </button>
                <?php endforeach; ?>
            </div>
        </aside>

        <div class="shop-content">
            <?php foreach ($shop_categories as $cat_key => $cat): ?>
            <?php if (!empty($items_by_cat[$cat_key])): ?>
            <div class="shop-section" data-cat="<?= $cat_key ?>" style="display:none;">
                <h2 style="color:<?= $cat['color'] ?>;"><?= $cat['icon'] ?> <?= $cat['name'] ?></h2>
                <div class="shop-items">
                    <?php foreach ($items_by_cat[$cat_key] as $key => $item): ?>
                    <div class="shop-card cat-<?= $cat_key ?>">
                        <span class="cat-tag"><?= $cat['icon'] ?></span>
                        <div class="item-icon"><?= $cat['icon'] ?></div>
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <div class="desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>
                        <div class="cost"><?= $item['cost'] ?> <span class="pts">очков</span></div>
                        <form method="post">
                            <input type="hidden" name="item_key" value="<?= $key ?>">
                            <button type="submit" name="buy" class="btn btn-sm"
                                <?= (int)$userData['points'] < $item['cost'] ? 'disabled' : '' ?>>
                                <?= (int)$userData['points'] < $item['cost'] ? 'Не хватает' : 'Купить' ?>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>

            <div class="shop-section" data-cat="all">
                <h2>📋 Все товары</h2>
                <div class="shop-items">
                    <?php foreach ($donate_items as $key => $item): ?>
                    <?php $cat_color = $shop_categories[$item['category']]['color'] ?? '#888'; ?>
                    <?php $cat_icon = $shop_categories[$item['category']]['icon'] ?? '📦'; ?>
                    <div class="shop-card cat-<?= $item['category'] ?>">
                        <span class="cat-tag"><?= $cat_icon ?></span>
                        <div class="item-icon"><?= $cat_icon ?></div>
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <div class="desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>
                        <div class="cost"><?= $item['cost'] ?> <span class="pts">очков</span></div>
                        <form method="post">
                            <input type="hidden" name="item_key" value="<?= $key ?>">
                            <button type="submit" name="buy" class="btn btn-sm"
                                <?= (int)$userData['points'] < $item['cost'] ? 'disabled' : '' ?>>
                                <?= (int)$userData['points'] < $item['cost'] ? 'Не хватает' : 'Купить' ?>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const catBtns = document.querySelectorAll('.cat-btn');
    const sections = document.querySelectorAll('.shop-section');
    function showCategory(cat) {
        sections.forEach(s => s.style.display = s.dataset.cat === cat ? 'block' : 'none');
        catBtns.forEach(b => b.classList.toggle('active', b.dataset.cat === cat));
    }
    catBtns.forEach(btn => {
        btn.addEventListener('click', () => showCategory(btn.dataset.cat));
    });
});
</script>

<footer style="margin-top: 40px;"><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
