<?php require_once 'config.php';
$password = 'admin123';
if (!isset($_SESSION['admin']) && (!isset($_POST['apass']) || $_POST['apass'] !== $password)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { $err = 'Неверный пароль админа'; }
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Админка</title><link rel="stylesheet" href="style.css"></head><body><header><div class="header-inner"><a href="index.php" class="logo-link">'.$site_name.'</a></div></header><div class="container"><div class="form-card animate-in"><h1>🔐 Вход в админку</h1>';
    if (isset($err)) echo '<div class="msg msg-error">'.$err.'</div>';
    echo '<form method="post"><input type="password" name="apass" placeholder="Пароль администратора" required><button type="submit" class="btn">Войти</button></form></div></div></body></html>';
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
<title>Админ-панель</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link">⚡ Admin</a>
        <nav class="nav">
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button>
                <div class="dropdown-content">
                    <a href="snake.php">🐍 Змейка</a>
                    <a href="tetris.php">🧊 Тетрис</a>
                    <a href="2048.php">🔢 2048</a>
                    <a href="tictactoe.php">⭕ Крестики-нолики</a>
                    <a href="guess.php">❓ Угадай число</a>
                    <a href="memory.php">🃏 Память</a>
                    <a href="clicker.php">👆 Кликер</a>
                    <a href="quiz.php">📝 Викторина</a>
                    <a href="flappy.php">🐦 Flappy Bird</a>
                    <a href="reaction.php">⚡ Reaction Test</a>
                </div>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Магазин</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
            <a href="admin.php" class="btn btn-sm btn-red">⚙️ Админ</a>
        </nav>
    </div>
</header>
<div class="container animate-in">
    <h1>🔧 Админ-панель</h1>

    <div class="admin-stats">
        <div class="admin-stat gold"><span class="num"><?= $pending_count ?></span><span class="lbl">⏳ Ожидают выдачи</span></div>
        <div class="admin-stat green"><span class="num"><?= $completed_donations ?></span><span class="lbl">✅ Выдано донатов</span></div>
        <div class="admin-stat blue"><span class="num"><?= $total_donations ?></span><span class="lbl">📦 Всего покупок</span></div>
        <div class="admin-stat"><span class="num" style="color:#00bfff;"><?= $total_users ?></span><span class="lbl">👥 Пользователей</span></div>
    </div>

    <div class="card" style="border-color:rgba(255,170,0,0.4);">
        <h2 style="color:#ffaa00;">⏳ Ожидают выдачи (<?= $pending_count ?>)</h2>
        <?php if ($pending_count === 0): ?>
            <p style="text-align:center;color:#888;padding:20px;">🎉 Нет ожидающих покупок</p>
        <?php endif; ?>
        <?php foreach ($pending as $d): ?>
        <div class="admin-card">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                <div>
                    <strong style="color:#00ff00;"><?= htmlspecialchars($d['minecraft_nick']) ?></strong>
                    <span style="color:#ffd700;"> — <?= htmlspecialchars($d['item_name']) ?></span>
                </div>
                <div style="display:flex;gap:6px;">
                    <a href="?complete=<?= $d['id'] ?>" class="btn btn-sm">✅ Выдано</a>
                    <a href="?cancel=<?= $d['id'] ?>" class="btn btn-sm btn-red">❌ Отмена</a>
                </div>
            </div>
            <div class="command-box">/ <?= htmlspecialchars(str_replace('%player%', $d['minecraft_nick'], $d['command'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2>👥 Пользователи</h2>
        <div class="table-wrap">
            <table>
                <tr><th>ID</th><th>Логин</th><th>Ник</th><th>Очки</th><th>Действие</th></tr>
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
                            <button type="submit" name="add_points" class="btn btn-sm btn-gold">+очки</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top:20px;">
        <h2>📋 Все покупки (последние 30)</h2>
        <div class="table-wrap">
            <table>
                <tr><th>Пользователь</th><th>Ник</th><th>Предмет</th><th>Статус</th><th>Дата</th></tr>
                <?php foreach ($allDons as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['minecraft_nick']) ?></td>
                    <td><?= htmlspecialchars($d['minecraft_nick']) ?></td>
                    <td><?= htmlspecialchars($d['item_name']) ?></td>
                    <td class="status-<?= $d['status'] ?>">
                        <?= $d['status'] === 'pending' ? '⏳' : ($d['status'] === 'completed' ? '✅' : '❌') ?> <?= $d['status'] ?>
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
