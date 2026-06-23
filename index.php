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
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button>
                <div class="dropdown-content">
                    <a href="snake.php">?? ������</a>
                    <a href="tetris.php">?? ������</a>
                    <a href="2048.php">?? 2048</a>
                    <a href="tictactoe.php">? ��������-������</a>
                    <a href="guess.php">? ������ �����</a>
                    <a href="memory.php">?? ������</a>
                    <a href="clicker.php">?? ������</a>
                    <a href="quiz.php">?? ���������</a>
                    <a href="flappy.php">?? Flappy Bird</a>
                    <a href="reaction.php">? Reaction Test</a>
                    <a href="minesweeper.php">?? ����</a>
                    <a href="hangman.php">?? ��������</a>
                    <a href="simon.php">?? ������</a>
                    <a href="pong.php">?? ����</a>
                    <a href="invaders.php">?? ���������</a>
                    <a href="breakout.php">?? ��������</a>
                    <a href="sudoku.php">?? ������</a>
                    <a href="wordle.php">?? ������</a>
                    <a href="dino.php">?? ����������</a>
                    <a href="rps.php">? ������-�������</a>
                    <a href="typing.php">?? ������</a>
                    <a href="color_match.php">?? ����</a>
                    <a href="balloon.php">?? ������</a>
                    <a href="whack.php">?? ����</a>
                    <a href="hanoi.php">?? �����</a>
                    <a href="connect4.php">?? 4 � ���</a>
                    <a href="math.php">?? ����������</a>
                    <a href="fifteen.php">?? ��������</a>
                    <a href="asteroids.php">?? ���������</a>
                    <a href="pacman.php">?? ������</a>
                </div>
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
                <p>Складывай падающие блоки, собирай линии. Блоки проходят сквозь стены! Каждые 10 линий — новый уровень.</p>
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
                <p>Стирай покрытие мышкой и забери приз! <strong style="color:#ffd700;">10 карт</strong> в день. До 500 очков.</p>
                <div class="game-stats"><span>🎯 До 500 очков</span></div>
                <?php if (isAuth()): ?><a href="scratch.php" class="btn btn-purple">Открыть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a3a1a,#2a5a2a);">🔢</div>
            <div class="game-body">
                <h3>2048</h3>
                <p>Складывай плитки с одинаковыми числами, чтобы получить 2048! Классическая головоломка.</p>
                <div class="game-stats"><span>🎯 Очки за слияния</span></div>
                <?php if (isAuth()): ?><a href="2048.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a3a,#2a2a5a);">❌</div>
            <div class="game-body">
                <h3>Крестики-нолики</h3>
                <p>Сражайся против ИИ в классической игре. Победа = 100 очков!</p>
                <div class="game-stats"><span>🎯 +100 за победу</span></div>
                <?php if (isAuth()): ?><a href="tictactoe.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a2a1a,#5a3a2a);">❓</div>
            <div class="game-body">
                <h3>Угадай число</h3>
                <p>Компьютер загадал число от 1 до 100. Угадай его! Меньше попыток — больше очков.</p>
                <div class="game-stats"><span>🎯 До 100 очков</span></div>
                <?php if (isAuth()): ?><a href="guess.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a3a,#2a3a5a);">🃏</div>
            <div class="game-body">
                <h3>Память</h3>
                <p>Найди все пары карточек. Чем меньше ходов — тем больше очков!</p>
                <div class="game-stats"><span>🎯 Очки за пары</span></div>
                <?php if (isAuth()): ?><a href="memory.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a1a,#5a2a2a);">🖱️</div>
            <div class="game-body">
                <h3>Кликер</h3>
                <p>Кликни как можно больше раз за 10 секунд! 1 клик = 10 очков.</p>
                <div class="game-stats"><span>🎯 До 500+ очков</span></div>
                <?php if (isAuth()): ?><a href="clicker.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a1a,#4a4a2a);">📝</div>
            <div class="game-body">
                <h3>Викторина</h3>
                <p>Ответь на 10 вопросов из разных категорий. Каждый правильный ответ = 10 очков!</p>
                <div class="game-stats"><span>🎯 До 100 очков</span></div>
                <?php if (isAuth()): ?><a href="quiz.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a3a2a,#2a5a3a);">🐦</div>
            <div class="game-body">
                <h3>Flappy Bird</h3>
                <p>Проведи птичку через все трубы! Управление — пробел или клик.</p>
                <div class="game-stats"><span>🎯 За пролёт</span></div>
                <?php if (isAuth()): ?><a href="flappy.php" class="btn btn-blue">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a3a,#3a2a5a);">⚡</div>
            <div class="game-body">
                <h3>Reaction Test</h3>
                <p>Проверь скорость реакции! Нажми на зелёный квадрат как можно быстрее.</p>
                <div class="game-stats"><span>🎯 Время в ms</span></div>
                <?php if (isAuth()): ?><a href="reaction.php" class="btn btn-purple">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a2a1a,#5a3a2a);">💣</div>
            <div class="game-body">
                <h3>Сапёр</h3>
                <p>Найди все мины на поле 9×9. ЛКМ — открыть, ПКМ — флаг.</p>
                <div class="game-stats"><span>🏆 +10 за клетку</span></div>
                <?php if (isAuth()): ?><a href="minesweeper.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a3a,#4a2a5a);">👻</div>
            <div class="game-body">
                <h3>Виселица</h3>
                <p>Угадай слово по буквам, пока человечка не повесили!</p>
                <div class="game-stats"><span>🏆 За угаданное слово</span></div>
                <?php if (isAuth()): ?><a href="hangman.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a1a,#5a2a2a);">🔴</div>
            <div class="game-body">
                <h3>Саймон говорит</h3>
                <p>Запомни последовательность цветов и повтори её!</p>
                <div class="game-stats"><span>🏆 +50 за раунд</span></div>
                <?php if (isAuth()): ?><a href="simon.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a3a,#2a3a5a);">🏓</div>
            <div class="game-body">
                <h3>Понг</h3>
                <p>Классический теннис. Управляй ракеткой мышкой!</p>
                <div class="game-stats"><span>🏆 +100 за победу</span></div>
                <?php if (isAuth()): ?><a href="pong.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#0a0a2e,#1a1a4a);">👾</div>
            <div class="game-body">
                <h3>Космические захватчики</h3>
                <p>Отбивай атаки пришельцев в космосе!</p>
                <div class="game-stats"><span>🏆 +10 за убийство</span></div>
                <?php if (isAuth()): ?><a href="invaders.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a00,#4a2a00);">🧱</div>
            <div class="game-body">
                <h3>Арканоид</h3>
                <p>Разбей все кирпичики мячом! 3 жизни.</p>
                <div class="game-stats"><span>🏆 +10 за кирпич</span></div>
                <?php if (isAuth()): ?><a href="breakout.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a1a,#2a4a2a);">🧩</div>
            <div class="game-body">
                <h3>Судоку</h3>
                <p>Заполни сетку 9×9 цифрами от 1 до 9.</p>
                <div class="game-stats"><span>🏆 До 500 очков</span></div>
                <?php if (isAuth()): ?><a href="sudoku.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a00,#4a4a00);">🔤</div>
            <div class="game-body">
                <h3>Вордли</h3>
                <p>Угадай слово из 5 букв за 6 попыток!</p>
                <div class="game-stats"><span>🏆 До 600 очков</span></div>
                <?php if (isAuth()): ?><a href="wordle.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a00,#2a4a00);">🦖</div>
            <div class="game-body">
                <h3>Динозаврик</h3>
                <p>Прыгай через кактусы, как Chrome Dino!</p>
                <div class="game-stats"><span>🏆 За препятствия</span></div>
                <?php if (isAuth()): ?><a href="dino.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a2a,#4a2a4a);">✊</div>
            <div class="game-body">
                <h3>Камень-Ножницы-Бумага</h3>
                <p>Сыграй против компьютера до 3 побед!</p>
                <div class="game-stats"><span>🏆 +100 за победу</span></div>
                <?php if (isAuth()): ?><a href="rps.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a2a,#2a2a4a);">⌨️</div>
            <div class="game-body">
                <h3>Тест печати</h3>
                <p>Печатай слова быстро и правильно за 30 сек!</p>
                <div class="game-stats"><span>🏆 За символы</span></div>
                <?php if (isAuth()): ?><a href="typing.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a1a,#4a4a2a);">🎨</div>
            <div class="game-body">
                <h3>Цветовая реакция</h3>
                <p>Нажимай на цвет текста, а не на слово!</p>
                <div class="game-stats"><span>🏆 +50 за верно</span></div>
                <?php if (isAuth()): ?><a href="color_match.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a2a,#5a2a4a);">🎈</div>
            <div class="game-body">
                <h3>Лопни шарик</h3>
                <p>Лопай шарики разных цветов за 30 секунд!</p>
                <div class="game-stats"><span>🏆 Разные цвета</span></div>
                <?php if (isAuth()): ?><a href="balloon.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a3a1a,#3a5a2a);">🔨</div>
            <div class="game-body">
                <h3>Ударь крота</h3>
                <p>Бей кротов, но избегай бомб! 30 секунд.</p>
                <div class="game-stats"><span>🏆 +10 за крота</span></div>
                <?php if (isAuth()): ?><a href="whack.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a2a,#2a4a4a);">🗼</div>
            <div class="game-body">
                <h3>Ханойская башня</h3>
                <p>Переложи все диски с одной башни на другую!</p>
                <div class="game-stats"><span>🏆 До 500 очков</span></div>
                <?php if (isAuth()): ?><a href="hanoi.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a1a,#4a2a2a);">🔴</div>
            <div class="game-body">
                <h3>Четыре в ряд</h3>
                <p>Собери 4 фишки в ряд против ИИ!</p>
                <div class="game-stats"><span>🏆 +200 за победу</span></div>
                <?php if (isAuth()): ?><a href="connect4.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a00,#2a2a00);">🧮</div>
            <div class="game-body">
                <h3>Математика</h3>
                <p>Реши 20 примеров за ограниченное время!</p>
                <div class="game-stats"><span>🏆 +25 за пример</span></div>
                <?php if (isAuth()): ?><a href="math.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#001a2a,#002a4a);">🧩</div>
            <div class="game-body">
                <h3>Пятнашки</h3>
                <p>Собери числа от 1 до 15 в правильном порядке!</p>
                <div class="game-stats"><span>🏆 До 500 очков</span></div>
                <?php if (isAuth()): ?><a href="fifteen.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#0a001a,#1a002a);">☄️</div>
            <div class="game-body">
                <h3>Астероиды</h3>
                <p>Уничтожай астероиды в космосе из пушки!</p>
                <div class="game-stats"><span>🏆 За уничтожение</span></div>
                <?php if (isAuth()): ?><a href="asteroids.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a0a,#2a2a1a);">👾</div>
            <div class="game-body">
                <h3>Пакман</h3>
                <p>Съешь все точки и убегай от привидений!</p>
                <div class="game-stats"><span>🏆 +10 за точку</span></div>
                <?php if (isAuth()): ?><a href="pacman.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026 — Сервер Minecraft 1.16.5</p></footer>
</body>
</html>
