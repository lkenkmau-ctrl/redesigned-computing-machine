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
<title><?= $site_name ?> - Добро пожаловать на  DonateCraft</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">🎮 Популярные игры ▾</button>
                <div class="dropdown-content">
                    
                </div>
            </div>
            <a href="games.php" class="btn btn-sm">🎮 Играть</a>            <?php if (isAuth()): ?>
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
    <section class="hero">
        <h1>Добро пожаловать на  DonateCraft</h1>
        <p>Играй, зарабатывай монеты и получай привилегии на лучшем Сервере!</p>
        <div class="hero-buttons">
            <?php if (!isAuth()): ?>
                <a href="register.php" class="btn btn-lg">Начать играть</a>
                <a href="login.php" class="btn btn-lg btn-outline">Уже есть аккаунт? Войти</a>
            <?php else: ?>
                <a href="games.php" class="btn btn-lg">Играть сейчас</a>
            <?php endif; ?>
        </div>
        <div class="stats-bar">
            <div class="stat-item"><span class="num"><?= $user_count ?></span><div class="lbl">игроков</div></div>
            <div class="stat-item"><span class="num"><?= $total_donations ?></span><div class="lbl">донатов</div></div>
        </div>
    </section>

    <section>
        <div class="section-header">
            <div class="section-tag">ПРЕИМУЩЕСТВА</div>
            <h2 class="section-title">Всё для комфортной игры</h2>
            <p class="section-subtitle">Играй, зарабатывай и покупай привилегии</p>
        </div>
        <div class="features-grid">
            <div class="feature-card"><span class="feature-icon">🛒</span><h3>Донат-магазин</h3><p>Покупай привилегии, валюту и наборы. Поддержи сервер и получи бонусы!</p></div>
            <div class="feature-card"><span class="feature-icon">🎮</span><h3>Игры с наградами</h3><p>Играй в мини-игры и зарабатывай монеты. Соревнуйся с друзьями!</p></div>
            <div class="feature-card"><span class="feature-icon">👑</span><h3>Привилегии</h3><p>Выделяйся уникальными рангами, цветом ника и особыми возможностями.</p></div>
            <div class="feature-card"><span class="feature-icon">💬</span><h3>Сообщество</h3><p>Присоединяйся к нашему дружному сообществу. Вместе веселее!</p></div>
        </div>
    </section>

    <section>
        <h2 class="section-title" style="text-align:center;margin-bottom:32px">Как это работает?</h2>
        <div class="steps-grid">
            <div class="step-card"><div class="step-num">1</div><p>Зарегистрируйся на сайте</p></div>
            <div class="step-card"><div class="step-num">2</div><p>Зарабатывай монеты в играх</p></div>
            <div class="step-card"><div class="step-num">3</div><p>Покупай привилегии</p></div>
            <div class="step-card"><div class="step-num">4</div><p>Активируй команды</p></div>
            <div class="step-card"><div class="step-num">5</div><p>Играй с комфортом!</p></div>
        </div>
    </section>

    <section>
        <div class="section-header">
            <div class="section-tag">🎮</div>
            <h2 class="section-title">Популярные игры</h2>
            <p class="section-subtitle">Играй и зарабатывай монеты в любой игре!</p>
        </div>
        <div class="games-grid">            <div class="game-card animate-in delay-1">
                <div class="game-preview game-snake">🐍</div>
                <div class="game-body">
                    <h3>Змейка</h3>
                    <p>Классическая змейка. Собирай яблоки, расти и не врежайся в стены!</p>
                    <div class="game-stats"><span>+100 за уровень</span></div>
                    <?php if (isAuth()): ?><a href="snake.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-2">
                <div class="game-preview game-tetris">🧊</div>
                <div class="game-body">
                    <h3>Тетрис</h3>
                    <p>Легендарная головоломка. Складывай падающие блоки в ряды и набирай очки!</p>
                    <div class="game-stats"><span>+100 за уровень</span></div>
                    <?php if (isAuth()): ?><a href="tetris.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-3">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🎡</div>
                <div class="game-body">
                    <h3>Колесо</h3>
                    <p>Крути колесо фортуны и выигрывай до 1000 монет! 5 попыток в день.</p>
                    <div class="game-stats"><span>до 1000</span></div>
                    <?php if (isAuth()): ?><a href="wheel.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-4">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🪩</div>
                <div class="game-body">
                    <h3>Скретч</h3>
                    <p>Стирай билеты и выигрывай до 500 монет! 10 билетов в день.</p>
                    <div class="game-stats"><span>до 500</span></div>
                    <?php if (isAuth()): ?><a href="scratch.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-1">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🔢</div>
                <div class="game-body">
                    <h3>2048</h3>
                    <p>Соединяй плитки с одинаковыми числами, чтобы получить 2048!</p>
                    <div class="game-stats"><span>2048!</span></div>
                    <?php if (isAuth()): ?><a href="2048.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-2">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">⭕</div>
                <div class="game-body">
                    <h3>Крестики</h3>
                    <p>Сыграй партию в классические крестики-нолики. Победа = 100 монет!</p>
                    <div class="game-stats"><span>+100 за победу</span></div>
                    <?php if (isAuth()): ?><a href="tictactoe.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-3">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">❓</div>
                <div class="game-body">
                    <h3>Угадайка</h3>
                    <p>Угадай загаданное число от 1 до 100. Чем меньше попыток, тем лучше!</p>
                    <div class="game-stats"><span>+50 монет</span></div>
                    <?php if (isAuth()): ?><a href="guess.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-4">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🃏</div>
                <div class="game-body">
                    <h3>Мемори</h3>
                    <p>Найди все пары карточек. Тренируй память и зарабатывай монеты!</p>
                    <div class="game-stats"><span>+50 за пару</span></div>
                    <?php if (isAuth()): ?><a href="memory.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-1">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">👆</div>
                <div class="game-body">
                    <h3>Кликер</h3>
                    <p>Кликай как можно быстрее за 10 секунд! 1 клик = 10 монет.</p>
                    <div class="game-stats"><span>1 клик = 10</span></div>
                    <?php if (isAuth()): ?><a href="clicker.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-2">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">📝</div>
                <div class="game-body">
                    <h3>Викторина</h3>
                    <p>Ответь на 10 вопросов из разных категорий. Правильный ответ = 10 монет!</p>
                    <div class="game-stats"><span>+10 за ответ</span></div>
                    <?php if (isAuth()): ?><a href="quiz.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-3">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🐦</div>
                <div class="game-body">
                    <h3>Flappy Bird</h3>
                    <p>Управляй птичкой и пролетай через препятствия. Сколько очков наберёшь?</p>
                    <div class="game-stats"><span>за пролёт</span></div>
                    <?php if (isAuth()): ?><a href="flappy.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>            <div class="game-card animate-in delay-4">
                <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">⚡</div>
                <div class="game-body">
                    <h3>Reaction Test</h3>
                    <p>Проверь свою реакцию! Нажми как можно быстрее, когда увидишь сигнал!</p>
                    <div class="game-stats"><span>время в мс</span></div>
                    <?php if (isAuth()): ?><a href="reaction.php" class="btn">Играть</a><?php endif; ?>
                </div>
            </div>        </div>
    </section>
</div>
<footer><p>DonateCraft &copy; 2026 © Играем на Майнкрафт 1.16.5</p></footer>
</body>
</html>