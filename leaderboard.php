<?php require_once 'config.php';
$db = getDb();
$leaderboard = $db->query("
    SELECT u.id, u.username, u.minecraft_nick, u.points,
        COALESCE(s.snake_levels, 0) as snake_levels,
        COALESCE(s.tetris_levels, 0) as tetris_levels,
        COALESCE(s.total_games, 0) as total_games
    FROM users u
    LEFT JOIN (
        SELECT user_id,
            SUM(CASE WHEN game='snake' THEN level ELSE 0 END) as snake_levels,
            SUM(CASE WHEN game='tetris' THEN level ELSE 0 END) as tetris_levels,
            COUNT(*) as total_games
        FROM scores GROUP BY user_id
    ) s ON u.id = s.user_id
    ORDER BY u.points DESC LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);
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
            <?php if (isAuth()): ?>
                <a href="profile.php" class="btn btn-sm btn-outline">Профиль</a>
                <a href="donate.php" class="btn btn-sm">Магазин</a>
                <a href="index.php" class="btn btn-sm btn-outline">Главная</a>
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
            <?php foreach ($leaderboard as $u): ?>
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
            <?php if (count($leaderboard) === 0): ?>
            <tr><td colspan="7" style="text-align:center;color:#666;padding:30px;">Пока никто не играл. Будь первым!</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
