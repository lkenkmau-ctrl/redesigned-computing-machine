<?php require_once 'config.php';
$users_resp = supabaseSelect('users', ['select' => 'id']);
$user_count = count($users_resp);
$dons_resp = supabaseSelect('donations', [
    'select' => 'id',
    'where' => 'status=eq.completed'
]);
$total_donations = count($dons_resp);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $site_name ?> - Зарабатывай очки в мини-играх!</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <?php if (isAuth()): ?>
                <a href="profile.php" class="btn btn-sm btn-outline">Профиль</a>
                <a href="snake.php" class="btn btn-sm btn-outline">Змейка</a>
                <a href="tetris.php" class="btn btn-sm btn-outline">Тетрис</a>
                <a href="wheel.php" class="btn btn-sm btn-outline">Колесо</a>
                <a href="scratch.php" class="btn btn-sm btn-outline">Скретч</a>
                <a href="donate.php" class="btn btn-sm">Магазин</a>
                <a href="leaderboard.php" class="btn btn-sm btn-outline">Лидеры</a>
                <a href="logout.php" class="btn btn-sm btn-red">Выход</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-sm btn-outline">Вход</a>
                <a href="register.php" class="btn btn-sm">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<div class="container">
    <section class="hero animate-in">
        <h1>Добро пожаловать на DonateCraft</h1>
        <p>Зарабатывай очки, играя в классические мини-игры, и обменивай их на донат, привилегии, наборы и косметику на нашем сервере Minecraft 1.16.5!</p>
        <div class="hero-buttons">
            <?php if (!isAuth()): ?>
                <a href="register.php" class="btn" style="font-size:18px;padding:14px 36px;">Начать играть</a>
                <a href="login.php" class="btn btn-outline" style="font-size:18px;padding:14px 36px;">Уже есть аккаунт</a>
            <?php else: ?>
                <a href="snake.php" class="btn" style="font-size:18px;padding:14px 36px;">Играть в Змейку</a>
                <a href="tetris.php" class="btn btn-outline" style="font-size:18px;padding:14px 36px;">Играть в Тетрис</a>
            <?php endif; ?>
        </div>
        <div style="margin-top:20px;display:flex;gap:24px;justify-content:center;color:#666;font-size:14px;">
            <span>👥 <?= $user_count ?> игроков</span>
            <span>🎁 <?= $total_donations ?> выдано донатов</span>
        </div>
    </section>

    <section class="features">
        <div class="feature-card animate-in delay-1">
            <div class="feature-icon">🎮</div>
            <h3>Мини-игры</h3>
            <p>Классические Змейка и Тетрис прямо в браузере. Проходи уровни и зарабатывай очки!</p>
        </div>
        <div class="feature-card animate-in delay-2">
            <div class="feature-icon">🛒</div>
            <h3>Магазин доната</h3>
            <p>Трать очки на привилегии, валюту, кейсы, наборы, бустеры и косметику для Minecraft.</p>
        </div>
        <div class="feature-card animate-in delay-3">
            <div class="feature-icon">⚡</div>
            <h3>Мгновенная выдача</h3>
            <p>Плагин на сервере автоматически проверяет новые покупки и выдаёт предметы в игре.</p>
        </div>
        <div class="feature-card animate-in delay-4">
            <div class="feature-icon">🏆</div>
            <h3>Соревнование</h3>
            <p>Соревнуйся с другими игроками в таблице лидеров. Стань лучшим!</p>
        </div>
    </section>

    <h2 class="animate-in">Как это работает?</h2>
    <div class="steps animate-in">
        <div class="step"><div class="step-num">1</div><p>Зарегистрируйся и укажи свой Minecraft ник</p></div>
        <div class="step"><div class="step-num">2</div><p>Играй в Змейку или Тетрис, проходи уровни</p></div>
        <div class="step"><div class="step-num">3</div><p>Получай очки за каждый пройденный уровень</p></div>
        <div class="step"><div class="step-num">4</div><p>Покупай донат-предметы в магазине</p></div>
        <div class="step"><div class="step-num">5</div><p>Администратор выдаст всё на сервере!</p></div>
    </div>

    <h2 class="animate-in">🎮 Игры</h2>
    <div class="games-grid">
        <div class="game-card animate-in delay-1">
            <div class="game-preview game-snake">🐍</div>
            <div class="game-body">
                <h3>Змейка</h3>
                <p>Управляй змейкой, собирай еду и становись длиннее. Каждые 5 съеденных кусочков — новый уровень!</p>
                <div class="game-stats"><span>🎯 +100 очков за уровень</span></div>
                <?php if (isAuth()): ?><a href="snake.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview game-tetris">🧊</div>
            <div class="game-body">
                <h3>Тетрис</h3>
                <p>Складывай падающие блоки, собирай линии и зарабатывай очки. Каждые 10 линий — новый уровень!</p>
                <div class="game-stats"><span>🎯 +100 очков за уровень</span></div>
                <?php if (isAuth()): ?><a href="tetris.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a00,#5a2a00);">🎡</div>
            <div class="game-body">
                <h3>Колесо Фортуны</h3>
                <p>Крути колесо и выигрывай до 1000 очков! <strong style="color:#ffd700;">5 попыток</strong> в день.</p>
                <div class="game-stats"><span>🎯 До 1000 очков</span></div>
                <?php if (isAuth()): ?><a href="wheel.php" class="btn btn-gold">Крутить</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a003a,#4a005a);">🎰</div>
            <div class="game-body">
                <h3>Скретч Карта</h3>
                <p>Открывай карты, собирай 3 одинаковых символа и получай приз! <strong style="color:#ffd700;">10 карт</strong> в день.</p>
                <div class="game-stats"><span>🎯 До 500 очков</span></div>
                <?php if (isAuth()): ?><a href="scratch.php" class="btn btn-purple">Открыть</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026 — Сервер Minecraft 1.16.5</p></footer>
</body>
</html>
