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
<title>Таблица лидеров</title>
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
            <?php if (isAuth()): ?>
            <a href="donate.php" class="btn btn-sm">💰 Магазин</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
            <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-outline">Вход</a>
            <a href="register.php" class="btn btn-sm">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<div class="container">
    <h1>Таблица лидеров</h1>
    <p style="text-align:center;color:#888;margin-bottom:20px;">🏆 Топ-50 игроков по заработанным очкам</p>

    <div class="table-wrap animate-in">
        <table class="leader-table">
            <tr>
                <th>#</th>
                <th>Игрок</th>
                <th>Minecraft ник</th>
                <th>Очки</th>
                <th>Змейка</th>
                <th>Тетрис</th>
                <th>Игр</th>
            </tr>
            <?php foreach ($stats as $u): ?>
            <tr>
                <td class="rank rank-<?= $rank ?>">
                    <?php if ($rank === 1): ?><span class="leader-medal">🥇</span>
                    <?php elseif ($rank === 2): ?><span class="leader-medal">🥈</span>
                    <?php elseif ($rank === 3): ?><span class="leader-medal">🥉</span>
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
            <tr><td colspan="7" style="text-align:center;color:#666;padding:30px;">Пока никто не играл. Будь первым!</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
