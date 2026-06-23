<?php require_once 'config.php';
$password = 'admin123';
if (!isset($_SESSION['admin']) && (!isset($_POST['apass']) || $_POST['apass'] !== $password)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { $err = 'РќРµРІРµСЂРЅС‹Р№ РїР°СЂРѕР»СЊ Р°РґРјРёРЅР°'; }
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>РђРґРјРёРЅРєР°</title><link rel="stylesheet" href="style.css"></head><body><header><div class="header-inner"><a href="index.php" class="logo-link">'.$site_name.'</a></div></header><div class="container"><div class="form-card animate-in"><h1>рџ”ђ Р’С…РѕРґ РІ Р°РґРјРёРЅРєСѓ</h1>';
    if (isset($err)) echo '<div class="msg msg-error">'.$err.'</div>';
    echo '<form method="post"><input type="password" name="apass" placeholder="РџР°СЂРѕР»СЊ Р°РґРјРёРЅРёСЃС‚СЂР°С‚РѕСЂР°" required><button type="submit" class="btn">Р’РѕР№С‚Рё</button></form></div></div></body></html>';
    exit;
}
$_SESSION['admin'] = true;

if (isset($_GET['complete'])) {
    $result = supabaseUpdate('donations', ['status' => 'completed'], 'id=eq.' . (int)$_GET['complete']);
    if (isset($result['error'])) {
        error_log('supabaseUpdate complete error: ' . $result['error']);
    }
    header('Location: admin.php'); exit;
}
if (isset($_GET['cancel'])) {
    $dons = supabaseSelect('donations', [
        'select' => 'user_id,item_key,cost',
        'where' => 'id=eq.' . (int)$_GET['cancel'] . '&status=eq.pending'
    ]);
    $don = $dons[0] ?? null;
    if ($don) {
        $result = supabaseUpdate('donations', ['status' => 'cancelled'], 'id=eq.' . (int)$_GET['cancel']);
        if (isset($result['error'])) {
            error_log('supabaseUpdate cancel error: ' . $result['error']);
        }
        $user_resp = supabaseSelect('users', [
            'select' => 'points',
            'where' => 'id=eq.' . $don['user_id']
        ]);
        $current = !empty($user_resp) ? (int)$user_resp[0]['points'] : 0;
        supabaseUpdate('users', ['points' => $current + (int)$don['cost']], 'id=eq.' . $don['user_id']);
    }
    header('Location: admin.php'); exit;
}
if (isset($_POST['add_points'])) {
    $uid = (int)$_POST['uid'];
    $pts = (int)$_POST['points'];
    $user_resp = supabaseSelect('users', [
        'select' => 'points',
        'where' => 'id=eq.' . $uid
    ]);
    $current = !empty($user_resp) ? (int)$user_resp[0]['points'] : 0;
    $result = supabaseUpdate('users', ['points' => $current + $pts], 'id=eq.' . $uid);
    if (isset($result['error'])) {
        error_log('supabaseUpdate add_points error: ' . $result['error']);
    }
    header('Location: admin.php'); exit;
}

$pending = supabaseSelect('donations', [
    'select' => '*',
    'where' => 'status=eq.pending',
    'order' => 'created_at.desc'
]);
$allDons = supabaseSelect('donations', [
    'select' => '*',
    'order' => 'created_at.desc',
    'limit' => 30
]);
$users = supabaseSelect('users', [
    'select' => 'id,username,minecraft_nick,points',
    'order' => 'points.desc'
]);
$total_users = count($users);
$total_donations = count(supabaseSelect('donations', ['select' => 'id']));
$completed = supabaseSelect('donations', ['select' => 'id', 'where' => 'status=eq.completed']);
$completed_donations = count($completed);
$pending_count = count($pending);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>РђРґРјРёРЅ-РїР°РЅРµР»СЊ</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link">вљЎ Admin</a>
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
            <a href="admin.php" class="btn btn-sm btn-red">вљ™пёЏ РђРґРјРёРЅ</a>
        </nav>
    </div>
</header>
<div class="container animate-in">
    <h1>рџ”§ РђРґРјРёРЅ-РїР°РЅРµР»СЊ</h1>

    <div class="admin-stats">
        <div class="admin-stat gold"><span class="num"><?= $pending_count ?></span><span class="lbl">вЏі РћР¶РёРґР°СЋС‚ РІС‹РґР°С‡Рё</span></div>
        <div class="admin-stat green"><span class="num"><?= $completed_donations ?></span><span class="lbl">вњ… Р’С‹РґР°РЅРѕ РґРѕРЅР°С‚РѕРІ</span></div>
        <div class="admin-stat blue"><span class="num"><?= $total_donations ?></span><span class="lbl">рџ“¦ Р’СЃРµРіРѕ РїРѕРєСѓРїРѕРє</span></div>
        <div class="admin-stat"><span class="num" style="color:#00bfff;"><?= $total_users ?></span><span class="lbl">рџ‘Ґ РџРѕР»СЊР·РѕРІР°С‚РµР»РµР№</span></div>
    </div>

    <div class="card" style="border-color:rgba(255,170,0,0.4);">
        <h2 style="color:#ffaa00;">вЏі РћР¶РёРґР°СЋС‚ РІС‹РґР°С‡Рё (<?= $pending_count ?>)</h2>
        <?php if ($pending_count === 0): ?>
            <p style="text-align:center;color:#888;padding:20px;">рџЋ‰ РќРµС‚ РѕР¶РёРґР°СЋС‰РёС… РїРѕРєСѓРїРѕРє</p>
        <?php endif; ?>
        <?php foreach ($pending as $d): ?>
        <div class="admin-card">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                <div>
                    <strong style="color:#00ff00;"><?= htmlspecialchars($d['minecraft_nick']) ?></strong>
                    <span style="color:#ffd700;"> вЂ” <?= htmlspecialchars($d['item_name']) ?></span>
                </div>
                <div style="display:flex;gap:6px;">
                    <a href="?complete=<?= $d['id'] ?>" class="btn btn-sm">вњ… Р’С‹РґР°РЅРѕ</a>
                    <a href="?cancel=<?= $d['id'] ?>" class="btn btn-sm btn-red">вќЊ РћС‚РјРµРЅР°</a>
                </div>
            </div>
            <div class="command-box">/ <?= htmlspecialchars(str_replace('%player%', $d['minecraft_nick'], $d['command'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2>рџ‘Ґ РџРѕР»СЊР·РѕРІР°С‚РµР»Рё</h2>
        <div class="table-wrap">
            <table>
                <tr><th>ID</th><th>Р›РѕРіРёРЅ</th><th>РќРёРє</th><th>РћС‡РєРё</th><th>Р”РµР№СЃС‚РІРёРµ</th></tr>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['minecraft_nick']) ?></td>
                    <td><strong style="color:#ffd700;"><?= (int)$u['points'] ?></strong></td>
                    <td>
                        <form method="post" style="display:flex;gap:4px;">
                            <input type="hidden" name="uid" value="<?= $u['id'] ?>">
                            <input type="number" name="points" value="100" style="width:70px;padding:4px 8px;margin:0;" min="0">
                            <button type="submit" name="add_points" class="btn btn-sm btn-gold">+РѕС‡РєРё</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2>рџ“‹ Р’СЃРµ РїРѕРєСѓРїРєРё (РїРѕСЃР»РµРґРЅРёРµ 30)</h2>
        <div class="table-wrap">
            <table>
                <tr><th>РџРѕР»СЊР·РѕРІР°С‚РµР»СЊ</th><th>РќРёРє</th><th>РџСЂРµРґРјРµС‚</th><th>РЎС‚Р°С‚СѓСЃ</th><th>Р”Р°С‚Р°</th></tr>
                <?php foreach ($allDons as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['minecraft_nick']) ?></td>
                    <td><?= htmlspecialchars($d['minecraft_nick']) ?></td>
                    <td><?= htmlspecialchars($d['item_name']) ?></td>
                    <td class="status-<?= $d['status'] ?>">
                        <?= $d['status'] === 'pending' ? 'вЏі' : ($d['status'] === 'completed' ? 'вњ…' : 'вќЊ') ?> <?= $d['status'] ?>
                    </td>
                    <td style="font-size:12px;"><?= $d['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
