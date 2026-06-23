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
            $success = 'вњ… РџРѕРєСѓРїРєР° РѕС„РѕСЂРјР»РµРЅР°! РћР¶РёРґР°Р№С‚Рµ РІС‹РґР°С‡Рё РЅР° СЃРµСЂРІРµСЂРµ.';
        } else {
            $error = 'вќЊ РќРµРґРѕСЃС‚Р°С‚РѕС‡РЅРѕ РѕС‡РєРѕРІ! РќСѓР¶РЅРѕ ' . $item['cost'];
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
<title>РњР°РіР°Р·РёРЅ РґРѕРЅР°С‚Р°</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">рџЋ® РРіСЂС‹ в–ѕ</button>
                <div class="dropdown-content">
                    <a href="snake.php">?? Змейка</a>
                    <a href="tetris.php">?? Тетрис</a>
                    <a href="2048.php">?? 2048</a>
                    <a href="tictactoe.php">? Крестики-нолики</a>
                    <a href="guess.php">? Угадай число</a>
                    <a href="memory.php">?? Память</a>
                    <a href="clicker.php">?? Кликер</a>
                    <a href="quiz.php">?? Викторина</a>
                    <a href="flappy.php">?? Flappy Bird</a>
                    <a href="reaction.php">? Reaction Test</a>
                    <a href="minesweeper.php">?? Сапёр</a>
                    <a href="hangman.php">?? Виселица</a>
                    <a href="simon.php">?? Саймон</a>
                    <a href="pong.php">?? Понг</a>
                    <a href="invaders.php">?? Инвейдеры</a>
                    <a href="breakout.php">?? Арканоид</a>
                    <a href="sudoku.php">?? Судоку</a>
                    <a href="wordle.php">?? Вордли</a>
                    <a href="dino.php">?? Динозаврик</a>
                    <a href="rps.php">? Камень-Ножницы</a>
                    <a href="typing.php">?? Печать</a>
                    <a href="color_match.php">?? Цвет</a>
                    <a href="balloon.php">?? Шарики</a>
                    <a href="whack.php">?? Крот</a>
                    <a href="hanoi.php">?? Ханой</a>
                    <a href="connect4.php">?? 4 в ряд</a>
                    <a href="math.php">?? Математика</a>
                    <a href="fifteen.php">?? Пятнашки</a>
                    <a href="asteroids.php">?? Астероиды</a>
                    <a href="pacman.php">?? Пакман</a>
                </div>
            </div>
            <a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a>
            <a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a>
        </nav>
    </div>
</header>
<div class="container animate-in">
    <h1>рџ›’ РњР°РіР°Р·РёРЅ РґРѕРЅР°С‚Р°</h1>

    <div class="shop-header">
        <div class="balance-badge">
            <span class="icon">в­ђ</span> <?= (int)$userData['points'] ?> РѕС‡РєРѕРІ
        </div>
        <div>
            <a href="snake.php" class="btn btn-sm btn-outline">рџђЌ Р—РјРµР№РєР°</a>
            <a href="tetris.php" class="btn btn-sm btn-outline">рџ§Љ РўРµС‚СЂРёСЃ</a>
            <a href="wheel.php" class="btn btn-sm btn-outline">рџЋЎ РљРѕР»РµСЃРѕ</a>
            <a href="scratch.php" class="btn btn-sm btn-outline">рџЋ° РЎРєСЂРµС‚С‡</a>
        </div>
    </div>

    <?php if ($success): ?><div class="msg msg-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="msg msg-error"><?= $error ?></div><?php endif; ?>

    <div class="shop-layout">
        <aside class="shop-sidebar">
            <div class="shop-categories" id="categories">
                <button class="cat-btn active" data-cat="all"><span class="icon">рџ“‹</span> Р’СЃРµ</button>
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
                        <div class="cost"><?= $item['cost'] ?> <span class="pts">РѕС‡РєРѕРІ</span></div>
                        <form method="post">
                            <input type="hidden" name="item_key" value="<?= $key ?>">
                            <button type="submit" name="buy" class="btn btn-sm"
                                <?= (int)$userData['points'] < $item['cost'] ? 'disabled' : '' ?>>
                                <?= (int)$userData['points'] < $item['cost'] ? 'РќРµ С…РІР°С‚Р°РµС‚' : 'РљСѓРїРёС‚СЊ' ?>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>

            <div class="shop-section" data-cat="all">
                <h2>рџ“‹ Р’СЃРµ С‚РѕРІР°СЂС‹</h2>
                <div class="shop-items">
                    <?php foreach ($donate_items as $key => $item): ?>
                    <?php $cat_color = $shop_categories[$item['category']]['color'] ?? '#888'; ?>
                    <?php $cat_icon = $shop_categories[$item['category']]['icon'] ?? 'рџ“¦'; ?>
                    <div class="shop-card cat-<?= $item['category'] ?>">
                        <span class="cat-tag"><?= $cat_icon ?></span>
                        <div class="item-icon"><?= $cat_icon ?></div>
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <div class="desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>
                        <div class="cost"><?= $item['cost'] ?> <span class="pts">РѕС‡РєРѕРІ</span></div>
                        <form method="post">
                            <input type="hidden" name="item_key" value="<?= $key ?>">
                            <button type="submit" name="buy" class="btn btn-sm"
                                <?= (int)$userData['points'] < $item['cost'] ? 'disabled' : '' ?>>
                                <?= (int)$userData['points'] < $item['cost'] ? 'РќРµ С…РІР°С‚Р°РµС‚' : 'РљСѓРїРёС‚СЊ' ?>
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
