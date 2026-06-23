<?php require_once 'config.php';

$users = supabaseSelect('users', ['select' => 'id,username,minecraft_nick,points']);
$all_scores = supabaseSelect('scores', ['select' => 'user_id,game,level']);

$stats = [];
foreach ($users as $u) {
    $stats[$u['id']] = [
        'username' => $u['username'],
        'minecraft_nick' => $u['minecraft_nick'],
        'points' => (int)$u['points'],
        'snake_levels' => 0,
        'tetris_levels' => 0,
        'total_games' => 0,
    ];
}
foreach ($all_scores as $s) {
    $uid = $s['user_id'];
    if (isset($stats[$uid])) {
        if ($s['game'] === 'snake') $stats[$uid]['snake_levels'] += (int)$s['level'];
        if ($s['game'] === 'tetris') $stats[$uid]['tetris_levels'] += (int)$s['level'];
        $stats[$uid]['total_games']++;
    }
}

usort($stats, fn($a, $b) => $b['points'] - $a['points']);
$stats = array_slice($stats, 0, 50);
$rank = 1;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>РўР°Р±Р»РёС†Р° Р»РёРґРµСЂРѕРІ</title>
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
            <?php if (isAuth()): ?>
            <a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a>
            <a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a>
            <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-outline">Р’С…РѕРґ</a>
            <a href="register.php" class="btn btn-sm">Р РµРіРёСЃС‚СЂР°С†РёСЏ</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<div class="container">
    <h1>РўР°Р±Р»РёС†Р° Р»РёРґРµСЂРѕРІ</h1>
    <p style="text-align:center;color:#888;margin-bottom:20px;">рџЏ† РўРѕРї-50 РёРіСЂРѕРєРѕРІ РїРѕ Р·Р°СЂР°Р±РѕС‚Р°РЅРЅС‹Рј РѕС‡РєР°Рј</p>

    <div class="table-wrap animate-in">
        <table class="leader-table">
            <tr>
                <th>#</th>
                <th>РРіСЂРѕРє</th>
                <th>Minecraft РЅРёРє</th>
                <th>РћС‡РєРё</th>
                <th>Р—РјРµР№РєР°</th>
                <th>РўРµС‚СЂРёСЃ</th>
                <th>РРіСЂ</th>
            </tr>
            <?php foreach ($stats as $u): ?>
            <tr>
                <td class="rank rank-<?= $rank ?>">
                    <?php if ($rank === 1): ?><span class="leader-medal">рџҐ‡</span>
                    <?php elseif ($rank === 2): ?><span class="leader-medal">рџҐ€</span>
                    <?php elseif ($rank === 3): ?><span class="leader-medal">рџҐ‰</span>
                    <?php else: ?><?= $rank ?>
                    <?php endif; ?>
                </td>
                <td><span class="leader-avatar"><?= strtoupper(substr($u['username'], 0, 1)) ?></span> <?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['minecraft_nick']) ?></td>
                <td><strong style="color:#ffd700;"><?= (int)$u['points'] ?></strong></td>
                <td><?= (int)$u['snake_levels'] ?></td>
                <td><?= (int)$u['tetris_levels'] ?></td>
                <td><?= (int)$u['total_games'] ?></td>
            </tr>
            <?php $rank++; endforeach; ?>
            <?php if (count($stats) === 0): ?>
            <tr><td colspan="7" style="text-align:center;color:#666;padding:30px;">РџРѕРєР° РЅРёРєС‚Рѕ РЅРµ РёРіСЂР°Р». Р‘СѓРґСЊ РїРµСЂРІС‹Рј!</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
