<?php require_once 'config.php'; requireAuth();
$user_id = $_SESSION['user_id'];

$user_resp = supabaseSelect('users', [
    'select' => '*',
    'where' => 'id=eq.' . $user_id
]);
$userData = $user_resp[0] ?? null;
if (!$userData) { header('Location: logout.php'); exit; }

$all_scores = supabaseSelect('scores', [
    'select' => '*',
    'where' => 'user_id=eq.' . $user_id
]);

$snake_levels = 0; $tetris_levels = 0;
$snake_points = 0; $tetris_points = 0;
$snake_games = 0; $tetris_games = 0;
$wheel_points = 0; $wheel_games = 0;
$scratch_points = 0; $scratch_games = 0;
foreach ($all_scores as $s) {
    if ($s['game'] === 'snake') { $snake_levels += (int)$s['level']; $snake_points += (int)$s['points']; $snake_games++; }
    if ($s['game'] === 'tetris') { $tetris_levels += (int)$s['level']; $tetris_points += (int)$s['points']; $tetris_games++; }
    if ($s['game'] === 'wheel') { $wheel_points += (int)$s['points']; $wheel_games++; }
    if ($s['game'] === 'scratch') { $scratch_points += (int)$s['points']; $scratch_games++; }
}
$total_levels = $snake_levels + $tetris_levels;
$total_games = $snake_games + $tetris_games + $wheel_games + $scratch_games;

$all_dons = supabaseSelect('donations', [
    'select' => '*',
    'where' => 'user_id=eq.' . $user_id
]);
$total_spent = 0;
foreach ($all_dons as $d) {
    if (in_array($d['status'], ['completed', 'pending'])) {
        $total_spent += (int)$d['cost'];
    }
}

$donations = array_slice($all_dons, 0, 10);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Профиль - <?= htmlspecialchars($userData['username']) ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <a href="snake.php" class="btn btn-sm btn-outline">Змейка</a>
            <a href="tetris.php" class="btn btn-sm btn-outline">Тетрис</a>
            <a href="wheel.php" class="btn btn-sm btn-outline">Колесо</a>
            <a href="scratch.php" class="btn btn-sm btn-outline">Скретч</a>
            <a href="donate.php" class="btn btn-sm">Магазин</a>
            <a href="leaderboard.php" class="btn btn-sm btn-outline">Лидеры</a>
            <a href="index.php" class="btn btn-sm btn-outline">Главная</a>
            <a href="logout.php" class="btn btn-sm btn-red">Выход</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="profile-grid animate-in">
        <div class="card profile-sidebar">
            <div class="profile-avatar"><?= strtoupper(substr($userData['username'], 0, 1)) ?></div>
            <h2><?= htmlspecialchars($userData['username']) ?></h2>
            <div class="nick">⛏ <?= htmlspecialchars($userData['minecraft_nick']) ?></div>
            <div class="balances">
                <div class="bal-item"><span class="val"><?= (int)$userData['points'] ?></span><span class="lbl">⭐ Очков</span></div>
                <div class="bal-item"><span class="val"><?= $total_spent ?></span><span class="lbl">🎁 Потрачено</span></div>
            </div>
            <div style="margin-top:16px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                <a href="snake.php" class="btn btn-sm">🐍 Змейка</a>
                <a href="tetris.php" class="btn btn-sm btn-blue">🧊 Тетрис</a>
                <a href="wheel.php" class="btn btn-sm btn-gold">🎡 Колесо</a>
                <a href="scratch.php" class="btn btn-sm btn-purple">🎰 Скретч</a>
                <a href="donate.php" class="btn btn-sm">🛒 Магазин</a>
            </div>
        </div>

        <div class="card">
            <h2 style="margin-top:0;">Статистика</h2>
            <div class="stat-grid">
                <div class="stat-card"><span class="val"><?= $total_levels ?></span><span class="lbl">🎯 Всего уровней</span></div>
                <div class="stat-card"><span class="val"><?= $total_games ?></span><span class="lbl">🎮 Сыграно игр</span></div>
                <div class="stat-card"><span class="val" style="color:#00ff00;"><?= $snake_levels ?></span><span class="lbl">🐍 Змейка (ур.)</span></div>
                <div class="stat-card"><span class="val" style="color:#4488ff;"><?= $tetris_levels ?></span><span class="lbl">🧊 Тетрис (ур.)</span></div>
                <div class="stat-card"><span class="val" style="color:#ffd700;"><?= $wheel_points ?></span><span class="lbl">🎡 Колесо (оч.)</span></div>
                <div class="stat-card"><span class="val" style="color:#da70d6;"><?= $scratch_points ?></span><span class="lbl">🎰 Скретч (оч.)</span></div>
            </div>

            <h3 style="margin-top:16px;color:#aaa;">Прогресс игр</h3>
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span>🐍 Змейка</span><span><?= $snake_levels ?> уровней</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:min(<?= $snake_levels > 0 ? min($snake_levels * 10, 100) : 0 ?>%, 100%);background:linear-gradient(90deg,#00aa00,#00ff00);"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span>🧊 Тетрис</span><span><?= $tetris_levels ?> уровней</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:min(<?= $tetris_levels > 0 ? min($tetris_levels * 10, 100) : 0 ?>%, 100%);background:linear-gradient(90deg,#0044aa,#4488ff);"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($donations) > 0): ?>
    <div class="card animate-in">
        <h2>Последние покупки</h2>
        <div class="table-wrap">
            <table>
                <tr><th>Предмет</th><th>Статус</th><th>Дата</th></tr>
                <?php foreach ($donations as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['item_name']) ?></td>
                    <td class="status-<?= $d['status'] ?>">
                        <?= $d['status'] === 'pending' ? '⏳ Ожидает' : ($d['status'] === 'completed' ? '✅ Выдано' : '❌ Отменено') ?>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="card animate-in" style="text-align:center;padding:40px;">
        <p style="font-size:40px;margin-bottom:12px;">🛒</p>
        <p style="color:#888;">У вас пока нет покупок. Заработайте очки в играх и купите что-нибудь в магазине!</p>
        <a href="donate.php" class="btn" style="margin-top:16px;">Перейти в магазин</a>
    </div>
    <?php endif; ?>
</div>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
