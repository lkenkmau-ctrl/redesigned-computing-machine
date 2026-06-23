<?php require_once 'config.php'; requireAuth();
$user_id = $_SESSION['user_id'];

$user_resp = supabaseSelect('users', [
    'select' => '*',
    'where' => 'id=eq.' . $user_id
]);
$userData = $user_resp[0] ?? null;
if (!$userData) { header('Location: logout.php'); exit; }

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    $item_key = $_POST['item_key'];
    if (isset($donate_items[$item_key])) {
        $item = $donate_items[$item_key];
        if ((int)$userData['points'] >= $item['cost']) {
            $newPoints = (int)$userData['points'] - $item['cost'];
            supabaseUpdate('users', ['points' => $newPoints], 'id=eq.' . $user_id);

            supabaseInsert('donations', [
                'user_id' => $user_id,
                'item_key' => $item_key,
                'item_name' => $item['name'],
                'cost' => $item['cost'],
                'command' => $item['command'],
                'minecraft_nick' => $userData['minecraft_nick']
            ]);

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
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button>
                <div class="dropdown-content">
                    <a href="snake.php">рџђЌ Р—РјРµР№РєР°</a>
                    <a href="tetris.php">рџ§Љ РўРµС‚СЂРёСЃ</a>
                    <a href="2048.php">рџ”ў 2048</a>
                    <a href="tictactoe.php">в­• РљСЂРµСЃС‚РёРєРё-РЅРѕР»РёРєРё</a>
                    <a href="guess.php">вќ“ РЈРіР°РґР°Р№ С‡РёСЃР»Рѕ</a>
                    <a href="memory.php">рџѓЏ РџР°РјСЏС‚СЊ</a>
                    <a href="clicker.php">рџ‘† РљР»РёРєРµСЂ</a>
                    <a href="quiz.php">рџ“ќ Р’РёРєС‚РѕСЂРёРЅР°</a>
                    <a href="flappy.php">рџђ¦ Flappy Bird</a>
                    <a href="reaction.php">вљЎ Reaction Test</a>
                    <a href="minesweeper.php">рџ’Ј РЎР°РїС‘СЂ</a>
                    <a href="hangman.php">рџ‘» Р’РёСЃРµР»РёС†Р°</a>
                    <a href="simon.php">рџ”ґ РЎР°Р№РјРѕРЅ</a>
                    <a href="pong.php">рџЏ“ РџРѕРЅРі</a>
                    <a href="invaders.php">рџ‘ѕ РРЅРІРµР№РґРµСЂС‹</a>
                    <a href="breakout.php">рџ§± РђСЂРєР°РЅРѕРёРґ</a>
                    <a href="sudoku.php">рџ§© РЎСѓРґРѕРєСѓ</a>
                    <a href="wordle.php">рџ”¤ Р’РѕСЂРґР»Рё</a>
                    <a href="dino.php">рџ¦– Р”РёРЅРѕР·Р°РІСЂРёРє</a>
                    <a href="rps.php">вњЉ РљР°РјРµРЅСЊ-РќРѕР¶РЅРёС†С‹</a>
                    <a href="typing.php">вЊЁпёЏ РџРµС‡Р°С‚СЊ</a>
                    <a href="color_match.php">рџЋЁ Р¦РІРµС‚</a>
                    <a href="balloon.php">рџЋ€ РЁР°СЂРёРєРё</a>
                    <a href="whack.php">рџ”Ё РљСЂРѕС‚</a>
                    <a href="hanoi.php">рџ—ј РҐР°РЅРѕР№</a>
                    <a href="connect4.php">рџ”ґ 4 РІ СЂСЏРґ</a>
                    <a href="math.php">рџ§® РњР°С‚РµРјР°С‚РёРєР°</a>
                    <a href="fifteen.php">рџ§© РџСЏС‚РЅР°С€РєРё</a>
                    <a href="asteroids.php">в„пёЏ РђСЃС‚РµСЂРѕРёРґС‹</a>
                    <a href="pacman.php">рџ‘ѕ РџР°РєРјР°РЅ</a></div>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Магазин</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
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
