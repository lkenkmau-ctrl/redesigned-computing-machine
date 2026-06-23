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
<title>������� - <?= htmlspecialchars($userData['username']) ?></title>
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
                    <a href="minesweeper.php">💣 Сапёр</a>
                    <a href="hangman.php">👻 Виселица</a>
                    <a href="simon.php">🔴 Саймон</a>
                    <a href="pong.php">🏓 Понг</a>
                    <a href="invaders.php">👾 Инвейдеры</a>
                    <a href="breakout.php">🧱 Арканоид</a>
                    <a href="sudoku.php">🧩 Судоку</a>
                    <a href="wordle.php">🔤 Вордли</a>
                    <a href="dino.php">🦖 Динозаврик</a>
                    <a href="rps.php">✊ Камень-Ножницы</a>
                    <a href="typing.php">⌨️ Печать</a>
                    <a href="color_match.php">🎨 Цвет</a>
                    <a href="balloon.php">🎈 Шарики</a>
                    <a href="whack.php">🔨 Крот</a>
                    <a href="hanoi.php">🗼 Ханой</a>
                    <a href="connect4.php">🔴 4 в ряд</a>
                    <a href="math.php">🧮 Математика</a>
                    <a href="fifteen.php">🧩 Пятнашки</a>
                    <a href="asteroids.php">☄️ Астероиды</a>
                    <a href="pacman.php">👾 Пакман</a></div>
                <a href="games.php" class="btn btn-sm">🎮 Играть</a>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="profile-grid animate-in">
        <div class="card profile-sidebar">
            <div class="profile-avatar"><?= strtoupper(substr($userData['username'], 0, 1)) ?></div>
            <h2><?= htmlspecialchars($userData['username']) ?></h2>
            <div class="nick">? <?= htmlspecialchars($userData['minecraft_nick']) ?></div>
            <div class="balances">
                <div class="bal-item"><span class="val"><?= (int)$userData['points'] ?></span><span class="lbl">? �����</span></div>
                <div class="bal-item"><span class="val"><?= $total_spent ?></span><span class="lbl">?? ���������</span></div>
            </div>
            <div style="margin-top:16px;display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                <a href="snake.php" class="btn btn-sm">??</a>
                <a href="tetris.php" class="btn btn-sm btn-blue">??</a>
                <a href="2048.php" class="btn btn-sm" style="background:#3a5a3a;">??</a>
                <a href="wheel.php" class="btn btn-sm btn-gold">??</a>
                <a href="scratch.php" class="btn btn-sm btn-purple">??</a>
                <a href="tictactoe.php" class="btn btn-sm" style="background:#2a2a5a;">?</a>
                <a href="guess.php" class="btn btn-sm" style="background:#5a3a2a;">?</a>
                <a href="memory.php" class="btn btn-sm" style="background:#2a3a5a;">??</a>
                <a href="clicker.php" class="btn btn-sm" style="background:#5a2a2a;">???</a>
                <a href="quiz.php" class="btn btn-sm" style="background:#4a4a2a;">??</a>
                <a href="donate.php" class="btn btn-sm">??</a>
            </div>
        </div>

        <div class="card">
            <h2 style="margin-top:0;">����������</h2>
            <div class="stat-grid">
                <div class="stat-card"><span class="val"><?= $total_levels ?></span><span class="lbl">?? ����� �������</span></div>
                <div class="stat-card"><span class="val"><?= $total_games ?></span><span class="lbl">?? ������� ���</span></div>
                <div class="stat-card"><span class="val" style="color:#00ff00;"><?= $snake_levels ?></span><span class="lbl">?? ������ (��.)</span></div>
                <div class="stat-card"><span class="val" style="color:#4488ff;"><?= $tetris_levels ?></span><span class="lbl">?? ������ (��.)</span></div>
                <div class="stat-card"><span class="val" style="color:#ffd700;"><?= $wheel_points ?></span><span class="lbl">?? ������ (��.)</span></div>
                <div class="stat-card"><span class="val" style="color:#da70d6;"><?= $scratch_points ?></span><span class="lbl">?? ������ (��.)</span></div>
            </div>

            <h3 style="margin-top:16px;color:#aaa;">�������� ���</h3>
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span>?? ������</span><span><?= $snake_levels ?> �������</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:min(<?= $snake_levels > 0 ? min($snake_levels * 10, 100) : 0 ?>%, 100%);background:linear-gradient(90deg,#00aa00,#00ff00);"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span>?? ������</span><span><?= $tetris_levels ?> �������</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:min(<?= $tetris_levels > 0 ? min($tetris_levels * 10, 100) : 0 ?>%, 100%);background:linear-gradient(90deg,#0044aa,#4488ff);"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($donations) > 0): ?>
    <div class="card animate-in">
        <h2>��������� �������</h2>
        <div class="table-wrap">
            <table>
                <tr><th>�������</th><th>������</th><th>����</th></tr>
                <?php foreach ($donations as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['item_name']) ?></td>
                    <td class="status-<?= $d['status'] ?>">
                        <?= $d['status'] === 'pending' ? '? �������' : ($d['status'] === 'completed' ? '? ������' : '? ��������') ?>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="card animate-in" style="text-align:center;padding:40px;">
        <p style="font-size:40px;margin-bottom:12px;">??</p>
        <p style="color:#888;">� ��� ���� ��� �������. ����������� ���� � ����� � ������ ���-������ � ��������!</p>
        <a href="donate.php" class="btn" style="margin-top:16px;">������� � �������</a>
    </div>
    <?php endif; ?>
</div>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
