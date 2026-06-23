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
<title><?= $site_name ?> - Игровой сервер и донат-магазин!</title>
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
        <h1>����� ���������� �� DonateCraft</h1>
        <p>����������� ����, ����� � ������������ ����-����, � ��������� �� �� �����, ����������, ������ � ��������� �� ����� ������� Minecraft 1.16.5!</p>
        <div class="hero-buttons">
            <?php if (!isAuth()): ?>
                <a href="register.php" class="btn" style="font-size:18px;padding:14px 36px;">������ ������</a>
                <a href="login.php" class="btn btn-outline" style="font-size:18px;padding:14px 36px;">��� ���� �������</a>
            <?php else: ?>
                <a href="snake.php" class="btn" style="font-size:18px;padding:14px 36px;">������ � ������</a>
                <a href="tetris.php" class="btn btn-outline" style="font-size:18px;padding:14px 36px;">������ � ������</a>
            <?php endif; ?>
        </div>
        <div style="margin-top:20px;display:flex;gap:24px;justify-content:center;color:#666;font-size:14px;">
            <span>?? <?= $user_count ?> �������</span>
            <span>?? <?= $total_donations ?> ������ �������</span>
        </div>
    </section>

    <section class="features">
        <div class="feature-card animate-in delay-1">
            <div class="feature-icon">??</div>
            <h3>����-����</h3>
            <p>������������ ������ � ������ ����� � ��������. ������� ������ � ����������� ����!</p>
        </div>
        <div class="feature-card animate-in delay-2">
            <div class="feature-icon">??</div>
            <h3>������� ������</h3>
            <p>����� ���� �� ����������, ������, �����, ������, ������� � ��������� ��� Minecraft.</p>
        </div>
        <div class="feature-card animate-in delay-3">
            <div class="feature-icon">?</div>
            <h3>���������� ������</h3>
            <p>������ �� ������� ������������� ��������� ����� ������� � ����� �������� � ����.</p>
        </div>
        <div class="feature-card animate-in delay-4">
            <div class="feature-icon">??</div>
            <h3>������������</h3>
            <p>���������� � ������� �������� � ������� �������. ����� ������!</p>
        </div>
    </section>

    <h2 class="animate-in">��� ��� ��������?</h2>
    <div class="steps animate-in">
        <div class="step"><div class="step-num">1</div><p>��������������� � ����� ���� Minecraft ���</p></div>
        <div class="step"><div class="step-num">2</div><p>����� � ������ ��� ������, ������� ������</p></div>
        <div class="step"><div class="step-num">3</div><p>������� ���� �� ������ ���������� �������</p></div>
        <div class="step"><div class="step-num">4</div><p>������� �����-�������� � ��������</p></div>
        <div class="step"><div class="step-num">5</div><p>������������� ������ �� �� �������!</p></div>
    </div>

    <h2 class="animate-in">?? ����</h2>
    <div class="games-grid">
        <div class="game-card animate-in delay-1">
            <div class="game-preview game-snake">??</div>
            <div class="game-body">
                <h3>������</h3>
                <p>�������� �������, ������� ��� � ��������� �������. ������ 5 ��������� �������� � ����� �������!</p>
                <div class="game-stats"><span>?? +100 ����� �� �������</span></div>
                <?php if (isAuth()): ?><a href="snake.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview game-tetris">??</div>
            <div class="game-body">
                <h3>������</h3>
                <p>��������� �������� �����, ������� �����. ����� �������� ������ �����! ������ 10 ����� � ����� �������.</p>
                <div class="game-stats"><span>?? +100 ����� �� �������</span></div>
                <?php if (isAuth()): ?><a href="tetris.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a00,#5a2a00);">??</div>
            <div class="game-body">
                <h3>������ �������</h3>
                <p>����� ������ � ��������� �� 1000 �����! <strong style="color:#ffd700;">5 �������</strong> � ����.</p>
                <div class="game-stats"><span>?? �� 1000 �����</span></div>
                <?php if (isAuth()): ?><a href="wheel.php" class="btn btn-gold">�������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a003a,#4a005a);">??</div>
            <div class="game-body">
                <h3>������ �����</h3>
                <p>������ �������� ������ � ������ ����! <strong style="color:#ffd700;">10 ����</strong> � ����. �� 500 �����.</p>
                <div class="game-stats"><span>?? �� 500 �����</span></div>
                <?php if (isAuth()): ?><a href="scratch.php" class="btn btn-purple">�������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a3a1a,#2a5a2a);">??</div>
            <div class="game-body">
                <h3>2048</h3>
                <p>��������� ������ � ����������� �������, ����� �������� 2048! ������������ �����������.</p>
                <div class="game-stats"><span>?? ���� �� �������</span></div>
                <?php if (isAuth()): ?><a href="2048.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a3a,#2a2a5a);">?</div>
            <div class="game-body">
                <h3>��������-������</h3>
                <p>�������� ������ �� � ������������ ����. ������ = 100 �����!</p>
                <div class="game-stats"><span>?? +100 �� ������</span></div>
                <?php if (isAuth()): ?><a href="tictactoe.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a2a1a,#5a3a2a);">?</div>
            <div class="game-body">
                <h3>������ �����</h3>
                <p>��������� ������� ����� �� 1 �� 100. ������ ���! ������ ������� � ������ �����.</p>
                <div class="game-stats"><span>?? �� 100 �����</span></div>
                <?php if (isAuth()): ?><a href="guess.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a3a,#2a3a5a);">??</div>
            <div class="game-body">
                <h3>������</h3>
                <p>����� ��� ���� ��������. ��� ������ ����� � ��� ������ �����!</p>
                <div class="game-stats"><span>?? ���� �� ����</span></div>
                <?php if (isAuth()): ?><a href="memory.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a1a,#5a2a2a);">???</div>
            <div class="game-body">
                <h3>������</h3>
                <p>������ ��� ����� ������ ��� �� 10 ������! 1 ���� = 10 �����.</p>
                <div class="game-stats"><span>?? �� 500+ �����</span></div>
                <?php if (isAuth()): ?><a href="clicker.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a1a,#4a4a2a);">??</div>
            <div class="game-body">
                <h3>���������</h3>
                <p>������ �� 10 �������� �� ������ ���������. ������ ���������� ����� = 10 �����!</p>
                <div class="game-stats"><span>?? �� 100 �����</span></div>
                <?php if (isAuth()): ?><a href="quiz.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a3a2a,#2a5a3a);">??</div>
            <div class="game-body">
                <h3>Flappy Bird</h3>
                <p>������� ������ ����� ��� �����! ���������� � ������ ��� ����.</p>
                <div class="game-stats"><span>?? �� �����</span></div>
                <?php if (isAuth()): ?><a href="flappy.php" class="btn btn-blue">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a3a,#3a2a5a);">?</div>
            <div class="game-body">
                <h3>Reaction Test</h3>
                <p>������� �������� �������! ����� �� ������ ������� ��� ����� �������.</p>
                <div class="game-stats"><span>?? ����� � ms</span></div>
                <?php if (isAuth()): ?><a href="reaction.php" class="btn btn-purple">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a2a1a,#5a3a2a);">??</div>
            <div class="game-body">
                <h3>����</h3>
                <p>����� ��� ���� �� ���� 9?9. ��� � �������, ��� � ����.</p>
                <div class="game-stats"><span>?? +10 �� ������</span></div>
                <?php if (isAuth()): ?><a href="minesweeper.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a3a,#4a2a5a);">??</div>
            <div class="game-body">
                <h3>��������</h3>
                <p>������ ����� �� ������, ���� ��������� �� ��������!</p>
                <div class="game-stats"><span>?? �� ��������� �����</span></div>
                <?php if (isAuth()): ?><a href="hangman.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a1a,#5a2a2a);">??</div>
            <div class="game-body">
                <h3>������ �������</h3>
                <p>������� ������������������ ������ � ������� �!</p>
                <div class="game-stats"><span>?? +50 �� �����</span></div>
                <?php if (isAuth()): ?><a href="simon.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a3a,#2a3a5a);">??</div>
            <div class="game-body">
                <h3>����</h3>
                <p>������������ ������. �������� �������� ������!</p>
                <div class="game-stats"><span>?? +100 �� ������</span></div>
                <?php if (isAuth()): ?><a href="pong.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#0a0a2e,#1a1a4a);">??</div>
            <div class="game-body">
                <h3>����������� ����������</h3>
                <p>������� ����� ���������� � �������!</p>
                <div class="game-stats"><span>?? +10 �� ��������</span></div>
                <?php if (isAuth()): ?><a href="invaders.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a00,#4a2a00);">??</div>
            <div class="game-body">
                <h3>��������</h3>
                <p>������ ��� ��������� �����! 3 �����.</p>
                <div class="game-stats"><span>?? +10 �� ������</span></div>
                <?php if (isAuth()): ?><a href="breakout.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a1a,#2a4a2a);">??</div>
            <div class="game-body">
                <h3>������</h3>
                <p>������� ����� 9?9 ������� �� 1 �� 9.</p>
                <div class="game-stats"><span>?? �� 500 �����</span></div>
                <?php if (isAuth()): ?><a href="sudoku.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a00,#4a4a00);">??</div>
            <div class="game-body">
                <h3>������</h3>
                <p>������ ����� �� 5 ���� �� 6 �������!</p>
                <div class="game-stats"><span>?? �� 600 �����</span></div>
                <?php if (isAuth()): ?><a href="wordle.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a00,#2a4a00);">??</div>
            <div class="game-body">
                <h3>����������</h3>
                <p>������ ����� �������, ��� Chrome Dino!</p>
                <div class="game-stats"><span>?? �� �����������</span></div>
                <?php if (isAuth()): ?><a href="dino.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a2a,#4a2a4a);">?</div>
            <div class="game-body">
                <h3>������-�������-������</h3>
                <p>������ ������ ���������� �� 3 �����!</p>
                <div class="game-stats"><span>?? +100 �� ������</span></div>
                <?php if (isAuth()): ?><a href="rps.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a2a,#2a2a4a);">??</div>
            <div class="game-body">
                <h3>���� ������</h3>
                <p>������� ����� ������ � ��������� �� 30 ���!</p>
                <div class="game-stats"><span>?? �� �������</span></div>
                <?php if (isAuth()): ?><a href="typing.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a1a,#4a4a2a);">??</div>
            <div class="game-body">
                <h3>�������� �������</h3>
                <p>������� �� ���� ������, � �� �� �����!</p>
                <div class="game-stats"><span>?? +50 �� �����</span></div>
                <?php if (isAuth()): ?><a href="color_match.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a2a,#5a2a4a);">??</div>
            <div class="game-body">
                <h3>����� �����</h3>
                <p>����� ������ ������ ������ �� 30 ������!</p>
                <div class="game-stats"><span>?? ������ �����</span></div>
                <?php if (isAuth()): ?><a href="balloon.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a3a1a,#3a5a2a);">??</div>
            <div class="game-body">
                <h3>����� �����</h3>
                <p>��� ������, �� ������� ����! 30 ������.</p>
                <div class="game-stats"><span>?? +10 �� �����</span></div>
                <?php if (isAuth()): ?><a href="whack.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a2a,#2a4a4a);">??</div>
            <div class="game-body">
                <h3>��������� �����</h3>
                <p>�������� ��� ����� � ����� ����� �� ������!</p>
                <div class="game-stats"><span>?? �� 500 �����</span></div>
                <?php if (isAuth()): ?><a href="hanoi.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a1a,#4a2a2a);">??</div>
            <div class="game-body">
                <h3>������ � ���</h3>
                <p>������ 4 ����� � ��� ������ ��!</p>
                <div class="game-stats"><span>?? +200 �� ������</span></div>
                <?php if (isAuth()): ?><a href="connect4.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a00,#2a2a00);">??</div>
            <div class="game-body">
                <h3>����������</h3>
                <p>���� 20 �������� �� ������������ �����!</p>
                <div class="game-stats"><span>?? +25 �� ������</span></div>
                <?php if (isAuth()): ?><a href="math.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#001a2a,#002a4a);">??</div>
            <div class="game-body">
                <h3>��������</h3>
                <p>������ ����� �� 1 �� 15 � ���������� �������!</p>
                <div class="game-stats"><span>?? �� 500 �����</span></div>
                <?php if (isAuth()): ?><a href="fifteen.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#0a001a,#1a002a);">??</div>
            <div class="game-body">
                <h3>���������</h3>
                <p>��������� ��������� � ������� �� �����!</p>
                <div class="game-stats"><span>?? �� �����������</span></div>
                <?php if (isAuth()): ?><a href="asteroids.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a0a,#2a2a1a);">??</div>
            <div class="game-body">
                <h3>������</h3>
                <p>����� ��� ����� � ������ �� ����������!</p>
                <div class="game-stats"><span>?? +10 �� �����</span></div>
                <?php if (isAuth()): ?><a href="pacman.php" class="btn">������</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026 � ������ Minecraft 1.16.5</p></footer>
</body>
</html>
