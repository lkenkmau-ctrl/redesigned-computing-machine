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
<title>РџСЂРѕС„РёР»СЊ - <?= htmlspecialchars($userData['username']) ?></title>
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
<div class="container">
    <div class="profile-grid animate-in">
        <div class="card profile-sidebar">
            <div class="profile-avatar"><?= strtoupper(substr($userData['username'], 0, 1)) ?></div>
            <h2><?= htmlspecialchars($userData['username']) ?></h2>
            <div class="nick">в›Џ <?= htmlspecialchars($userData['minecraft_nick']) ?></div>
            <div class="balances">
                <div class="bal-item"><span class="val"><?= (int)$userData['points'] ?></span><span class="lbl">в­ђ РћС‡РєРѕРІ</span></div>
                <div class="bal-item"><span class="val"><?= $total_spent ?></span><span class="lbl">рџЋЃ РџРѕС‚СЂР°С‡РµРЅРѕ</span></div>
            </div>
            <div style="margin-top:16px;display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                <a href="snake.php" class="btn btn-sm">рџђЌ</a>
                <a href="tetris.php" class="btn btn-sm btn-blue">рџ§Љ</a>
                <a href="2048.php" class="btn btn-sm" style="background:#3a5a3a;">рџ”ў</a>
                <a href="wheel.php" class="btn btn-sm btn-gold">рџЋЎ</a>
                <a href="scratch.php" class="btn btn-sm btn-purple">рџЋ°</a>
                <a href="tictactoe.php" class="btn btn-sm" style="background:#2a2a5a;">вќЊ</a>
                <a href="guess.php" class="btn btn-sm" style="background:#5a3a2a;">вќ“</a>
                <a href="memory.php" class="btn btn-sm" style="background:#2a3a5a;">рџѓЏ</a>
                <a href="clicker.php" class="btn btn-sm" style="background:#5a2a2a;">рџ–±пёЏ</a>
                <a href="quiz.php" class="btn btn-sm" style="background:#4a4a2a;">рџ“ќ</a>
                <a href="donate.php" class="btn btn-sm">рџ›’</a>
            </div>
        </div>

        <div class="card">
            <h2 style="margin-top:0;">РЎС‚Р°С‚РёСЃС‚РёРєР°</h2>
            <div class="stat-grid">
                <div class="stat-card"><span class="val"><?= $total_levels ?></span><span class="lbl">рџЋЇ Р’СЃРµРіРѕ СѓСЂРѕРІРЅРµР№</span></div>
                <div class="stat-card"><span class="val"><?= $total_games ?></span><span class="lbl">рџЋ® РЎС‹РіСЂР°РЅРѕ РёРіСЂ</span></div>
                <div class="stat-card"><span class="val" style="color:#00ff00;"><?= $snake_levels ?></span><span class="lbl">рџђЌ Р—РјРµР№РєР° (СѓСЂ.)</span></div>
                <div class="stat-card"><span class="val" style="color:#4488ff;"><?= $tetris_levels ?></span><span class="lbl">рџ§Љ РўРµС‚СЂРёСЃ (СѓСЂ.)</span></div>
                <div class="stat-card"><span class="val" style="color:#ffd700;"><?= $wheel_points ?></span><span class="lbl">рџЋЎ РљРѕР»РµСЃРѕ (РѕС‡.)</span></div>
                <div class="stat-card"><span class="val" style="color:#da70d6;"><?= $scratch_points ?></span><span class="lbl">рџЋ° РЎРєСЂРµС‚С‡ (РѕС‡.)</span></div>
            </div>

            <h3 style="margin-top:16px;color:#aaa;">РџСЂРѕРіСЂРµСЃСЃ РёРіСЂ</h3>
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span>рџђЌ Р—РјРµР№РєР°</span><span><?= $snake_levels ?> СѓСЂРѕРІРЅРµР№</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:min(<?= $snake_levels > 0 ? min($snake_levels * 10, 100) : 0 ?>%, 100%);background:linear-gradient(90deg,#00aa00,#00ff00);"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span>рџ§Љ РўРµС‚СЂРёСЃ</span><span><?= $tetris_levels ?> СѓСЂРѕРІРЅРµР№</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:min(<?= $tetris_levels > 0 ? min($tetris_levels * 10, 100) : 0 ?>%, 100%);background:linear-gradient(90deg,#0044aa,#4488ff);"></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($donations) > 0): ?>
    <div class="card animate-in">
        <h2>РџРѕСЃР»РµРґРЅРёРµ РїРѕРєСѓРїРєРё</h2>
        <div class="table-wrap">
            <table>
                <tr><th>РџСЂРµРґРјРµС‚</th><th>РЎС‚Р°С‚СѓСЃ</th><th>Р”Р°С‚Р°</th></tr>
                <?php foreach ($donations as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['item_name']) ?></td>
                    <td class="status-<?= $d['status'] ?>">
                        <?= $d['status'] === 'pending' ? 'вЏі РћР¶РёРґР°РµС‚' : ($d['status'] === 'completed' ? 'вњ… Р’С‹РґР°РЅРѕ' : 'вќЊ РћС‚РјРµРЅРµРЅРѕ') ?>
                    </td>
                    <td><?= $d['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="card animate-in" style="text-align:center;padding:40px;">
        <p style="font-size:40px;margin-bottom:12px;">рџ›’</p>
        <p style="color:#888;">РЈ РІР°СЃ РїРѕРєР° РЅРµС‚ РїРѕРєСѓРїРѕРє. Р—Р°СЂР°Р±РѕС‚Р°Р№С‚Рµ РѕС‡РєРё РІ РёРіСЂР°С… Рё РєСѓРїРёС‚Рµ С‡С‚Рѕ-РЅРёР±СѓРґСЊ РІ РјР°РіР°Р·РёРЅРµ!</p>
        <a href="donate.php" class="btn" style="margin-top:16px;">РџРµСЂРµР№С‚Рё РІ РјР°РіР°Р·РёРЅ</a>
    </div>
    <?php endif; ?>
</div>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
