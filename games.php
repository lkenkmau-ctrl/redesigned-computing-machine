<?php require_once "config.php"; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🎮 Играть - <?= $site_name ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <a href="index.php" class="btn btn-sm">🏠 Главная</a>
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
            </div>
            <a href="games.php" class="btn btn-sm">🎮 Играть</a>
            <?php if (isAuth()): ?>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
            <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-outline">Войти</a>
            <a href="register.php" class="btn btn-sm">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<div class="container">
    <section class="hero animate-in">
        <h1>🎮 Играть</h1>
        <p>Выбирай любимую игру и зарабатывай монеты! Соревнуйся с друзьями и становись лучшим!</p>
    </section>

    <div class="games-grid"><div class="game-card animate-in delay-1"><div class="game-preview game-snake">🐍</div><div class="game-body"><h3>Змейка</h3><p>Классика.</p><?php if (isAuth()): ?><a href="snake.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview game-tetris">🧊</div><div class="game-body"><h3>Тетрис</h3><p>Легендарная головоломка.</p><?php if (isAuth()): ?><a href="tetris.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🔢</div><div class="game-body"><h3>2048</h3><p>Соединяй плитки!</p><?php if (isAuth()): ?><a href="2048.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">⭕</div><div class="game-body"><h3>Крестики-нолики</h3><p>Сыграй партию!</p><?php if (isAuth()): ?><a href="tictactoe.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">❓</div><div class="game-body"><h3>Угадай число</h3><p>Угадай от 1 до 100!</p><?php if (isAuth()): ?><a href="guess.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🃏</div><div class="game-body"><h3>Память</h3><p>Найди все пары!</p><?php if (isAuth()): ?><a href="memory.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">👆</div><div class="game-body"><h3>Кликер</h3><p>Кликай быстро!</p><?php if (isAuth()): ?><a href="clicker.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">📝</div><div class="game-body"><h3>Викторина</h3><p>Ответь на 10 вопросов!</p><?php if (isAuth()): ?><a href="quiz.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🐦</div><div class="game-body"><h3>Flappy Bird</h3><p>Управляй птицей!</p><?php if (isAuth()): ?><a href="flappy.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">⚡</div><div class="game-body"><h3>Reaction Test</h3><p>Проверь реакцию!</p><?php if (isAuth()): ?><a href="reaction.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">💣</div><div class="game-body"><h3>Сапёр</h3><p>Найди все мины!</p><?php if (isAuth()): ?><a href="minesweeper.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">👻</div><div class="game-body"><h3>Виселица</h3><p>Угадай слово!</p><?php if (isAuth()): ?><a href="hangman.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🔴</div><div class="game-body"><h3>Саймон</h3><p>Запоминай цвета!</p><?php if (isAuth()): ?><a href="simon.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🏓</div><div class="game-body"><h3>Понг</h3><p>Классика.</p><?php if (isAuth()): ?><a href="pong.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">👾</div><div class="game-body"><h3>Инвейдеры</h3><p>Стреляй по пришельцам!</p><?php if (isAuth()): ?><a href="invaders.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🧱</div><div class="game-body"><h3>Арканоид</h3><p>Разбей кирпичики!</p><?php if (isAuth()): ?><a href="breakout.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🧩</div><div class="game-body"><h3>Судоку</h3><p>Заполни сетку 9х9.</p><?php if (isAuth()): ?><a href="sudoku.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🔤</div><div class="game-body"><h3>Вордли</h3><p>Угадай слово!</p><?php if (isAuth()): ?><a href="wordle.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🦖</div><div class="game-body"><h3>Динозаврик</h3><p>Прыгай через препятствия!</p><?php if (isAuth()): ?><a href="dino.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">✊</div><div class="game-body"><h3>Камень-ножницы</h3><p>Сыграй в классику!</p><?php if (isAuth()): ?><a href="rps.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">⌨️</div><div class="game-body"><h3>Тест печати</h3><p>Проверь скорость!</p><?php if (isAuth()): ?><a href="typing.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🎨</div><div class="game-body"><h3>Цветовой тест</h3><p>Клацай по цветам!</p><?php if (isAuth()): ?><a href="color_match.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🎈</div><div class="game-body"><h3>Шарики</h3><p>Лопай шарики!</p><?php if (isAuth()): ?><a href="balloon.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🔨</div><div class="game-body"><h3>Крот</h3><p>Бей крота!</p><?php if (isAuth()): ?><a href="whack.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🗼</div><div class="game-body"><h3>Ханой</h3><p>Переложи кольца!</p><?php if (isAuth()): ?><a href="hanoi.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🔴</div><div class="game-body"><h3>Четыре в ряд</h3><p>Собери 4 фишки!</p><?php if (isAuth()): ?><a href="connect4.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🧮</div><div class="game-body"><h3>Математика</h3><p>Реши примеры!</p><?php if (isAuth()): ?><a href="math.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🧩</div><div class="game-body"><h3>Пятнашки</h3><p>Собери числа!</p><?php if (isAuth()): ?><a href="fifteen.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-1"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">☄️</div><div class="game-body"><h3>Астероиды</h3><p>Уничтожай астероиды!</p><?php if (isAuth()): ?><a href="asteroids.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-2"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">👾</div><div class="game-body"><h3>Пакман</h3><p>Съешь все точки!</p><?php if (isAuth()): ?><a href="pacman.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-3"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🎡</div><div class="game-body"><h3>Колесо фортуны</h3><p>Крути колесо!</p><?php if (isAuth()): ?><a href="wheel.php" class="btn">Играть</a><?php endif; ?></div></div><div class="game-card animate-in delay-4"><div class="game-preview" style="background:linear-gradient(135deg,#1a0a00,#2d1b00);">🪩</div><div class="game-body"><h3>Скретч</h3><p>Сотри и узнай!</p><?php if (isAuth()): ?><a href="scratch.php" class="btn">Играть</a><?php endif; ?></div></div></div><footer><p><?= $site_name ?> &copy; 2026 на сервере Minecraft 1.16.5</p></footer>
</body>
</html>