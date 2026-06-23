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
            <a href="index.php" class="btn btn-sm btn-ghost">🏠 Главная</a>
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">🎮 Популярные игры ▾</button>
                <div class="dropdown-content">
                    
                </div>
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
    <section class="games-hero">
        <h1>🎮 Играть</h1>
        <p>Выбирай любимую игру и зарабатывай монеты!</p>
    </section>

    <div class="games-filter" id="gameFilter">
        <button class="filter-btn active" data-filter="all">Все</button>
        <button class="filter-btn" data-filter="easy">Простые</button>
        <button class="filter-btn" data-filter="medium">Средние</button>
        <button class="filter-btn" data-filter="hard">Сложные</button>
    </div>

    <div class="games-grid">        <div class="game-card animate-in delay-1" data-difficulty="easy">
            <div class="game-preview game-snake">🐍</div>
            <div class="game-body">
                <h3>Змейка</h3>
                <p>Классическая змейка. Собирай яблоки и расти!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++100 за уровень</span>
                </div>
                <?php if (isAuth()): ?><a href="snake.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="medium">
            <div class="game-preview game-tetris">🧊</div>
            <div class="game-body">
                <h3>Тетрис</h3>
                <p>Легендарная головоломка. Складывай блоки!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++100 за уровень</span>
                </div>
                <?php if (isAuth()): ?><a href="tetris.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🔢</div>
            <div class="game-body">
                <h3>2048</h3>
                <p>Соединяй плитки до 2048!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">+50</span>
                </div>
                <?php if (isAuth()): ?><a href="2048.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">⭕</div>
            <div class="game-body">
                <h3>Крестики</h3>
                <p>Классическая игра на двоих.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++100 за победу</span>
                </div>
                <?php if (isAuth()): ?><a href="tictactoe.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">❓</div>
            <div class="game-body">
                <h3>Угадайка</h3>
                <p>Угадай число от 1 до 100.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++50 монет</span>
                </div>
                <?php if (isAuth()): ?><a href="guess.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🃏</div>
            <div class="game-body">
                <h3>Мемори</h3>
                <p>Найди все пары карточек!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++50 за пару</span>
                </div>
                <?php if (isAuth()): ?><a href="memory.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">👆</div>
            <div class="game-body">
                <h3>Кликер</h3>
                <p>Кликай быстро и ставь рекорды!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">+1 клик = 10</span>
                </div>
                <?php if (isAuth()): ?><a href="clicker.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">📝</div>
            <div class="game-body">
                <h3>Викторина</h3>
                <p>Проверь знания в 10 вопросах!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++10 за ответ</span>
                </div>
                <?php if (isAuth()): ?><a href="quiz.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="hard">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🐦</div>
            <div class="game-body">
                <h3>Flappy Bird</h3>
                <p>Управляй птичкой и лети!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-hard">Сложная</span>
                    <span class="points-display">+за пролёт</span>
                </div>
                <?php if (isAuth()): ?><a href="flappy.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">⚡</div>
            <div class="game-body">
                <h3>Reaction Test</h3>
                <p>Проверь скорость реакции!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">+время в мс</span>
                </div>
                <?php if (isAuth()): ?><a href="reaction.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">💣</div>
            <div class="game-body">
                <h3>Сапёр</h3>
                <p>Найди все мины на поле.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++10 за клетку</span>
                </div>
                <?php if (isAuth()): ?><a href="minesweeper.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">👻</div>
            <div class="game-body">
                <h3>Виселица</h3>
                <p>Угадай слово по буквам.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++50 за слово</span>
                </div>
                <?php if (isAuth()): ?><a href="hangman.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🔴</div>
            <div class="game-body">
                <h3>Саймон</h3>
                <p>Запоминай последовательность!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++50 за шаг</span>
                </div>
                <?php if (isAuth()): ?><a href="simon.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🏓</div>
            <div class="game-body">
                <h3>Понг</h3>
                <p>Классический теннис. Играй с друзом!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++100 за гол</span>
                </div>
                <?php if (isAuth()): ?><a href="pong.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="hard">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">👾</div>
            <div class="game-body">
                <h3>Инвэйдерс</h3>
                <p>Отбивай атаки пришельцев!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-hard">Сложная</span>
                    <span class="points-display">++10 за убитие</span>
                </div>
                <?php if (isAuth()): ?><a href="invaders.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🧱</div>
            <div class="game-body">
                <h3>Арканоид</h3>
                <p>Разбей все кирпичики!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++10 за кирпич</span>
                </div>
                <?php if (isAuth()): ?><a href="breakout.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="hard">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🧩</div>
            <div class="game-body">
                <h3>Судоку</h3>
                <p>Заполни сетку цифрами 1-9.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-hard">Сложная</span>
                    <span class="points-display">++50 за решение</span>
                </div>
                <?php if (isAuth()): ?><a href="sudoku.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🔤</div>
            <div class="game-body">
                <h3>Вордли</h3>
                <p>Угадай слово из 5 букв.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++50 за слово</span>
                </div>
                <?php if (isAuth()): ?><a href="wordle.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🦖</div>
            <div class="game-body">
                <h3>Дино</h3>
                <p>Прыгай через препятствия!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">+за прыжок</span>
                </div>
                <?php if (isAuth()): ?><a href="dino.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">✊</div>
            <div class="game-body">
                <h3>Камень-Ножницы</h3>
                <p>Сыграй в классику.</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++100 за победу</span>
                </div>
                <?php if (isAuth()): ?><a href="rps.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">⌨️</div>
            <div class="game-body">
                <h3>Печатание</h3>
                <p>Проверь скорость печати!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">+знаков в мин</span>
                </div>
                <?php if (isAuth()): ?><a href="typing.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🎨</div>
            <div class="game-body">
                <h3>Цвета</h3>
                <p>Кликай по цветам быстро!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++50 за раунд</span>
                </div>
                <?php if (isAuth()): ?><a href="color_match.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🎈</div>
            <div class="game-body">
                <h3>Шарики</h3>
                <p>Лопай шарики на время!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++10 за шар</span>
                </div>
                <?php if (isAuth()): ?><a href="balloon.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🔨</div>
            <div class="game-body">
                <h3>Крот</h3>
                <p>Бей крота молотком!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">++10 за удар</span>
                </div>
                <?php if (isAuth()): ?><a href="whack.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🗼</div>
            <div class="game-body">
                <h3>Ханой</h3>
                <p>Переложи все кольца!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++50 за решение</span>
                </div>
                <?php if (isAuth()): ?><a href="hanoi.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🔴</div>
            <div class="game-body">
                <h3>4 в ряд</h3>
                <p>Собери 4 фишки в ряд!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++200 за победу</span>
                </div>
                <?php if (isAuth()): ?><a href="connect4.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🧮</div>
            <div class="game-body">
                <h3>Математика</h3>
                <p>Решай примеры на время!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++25 за пример</span>
                </div>
                <?php if (isAuth()): ?><a href="math.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🧩</div>
            <div class="game-body">
                <h3>Пятнашки</h3>
                <p>Собери числа по порядку!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++50 за решение</span>
                </div>
                <?php if (isAuth()): ?><a href="fifteen.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-1" data-difficulty="hard">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">☄️</div>
            <div class="game-body">
                <h3>Астероиды</h3>
                <p>Уничтожай астероиды!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-hard">Сложная</span>
                    <span class="points-display">++10 за уничтожение</span>
                </div>
                <?php if (isAuth()): ?><a href="asteroids.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-2" data-difficulty="medium">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">👾</div>
            <div class="game-body">
                <h3>Пакман</h3>
                <p>Съешь все точки в лабиринте!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-medium">Средняя</span>
                    <span class="points-display">++10 за точку</span>
                </div>
                <?php if (isAuth()): ?><a href="pacman.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-3" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🎡</div>
            <div class="game-body">
                <h3>Колесо</h3>
                <p>Крути и выигрывай монеты!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">+до 1000</span>
                </div>
                <?php if (isAuth()): ?><a href="wheel.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>        <div class="game-card animate-in delay-4" data-difficulty="easy">
            <div class="game-preview" style="background:linear-gradient(135deg,#161820,#1e2230)">🪩</div>
            <div class="game-body">
                <h3>Скретч</h3>
                <p>Стирай билеты и выигрывай!</p>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-bottom:10px">
                    <span class="diff-badge diff-easy">Простая</span>
                    <span class="points-display">+до 500</span>
                </div>
                <?php if (isAuth()): ?><a href="scratch.php" class="btn">Играть</a><?php endif; ?>
            </div>
        </div>    </div>
</div>
<footer><p>DonateCraft &copy; 2026 © Играем на Майнкрафт 1.16.5</p></footer>
<script>
document.getElementById('gameFilter').addEventListener('click', function(e) {
    if (e.target.classList.contains('filter-btn')) {
        document.querySelectorAll('.filter-btn').forEach(function(b){b.classList.remove('active')});
        e.target.classList.add('active');
        var f = e.target.dataset.filter;
        document.querySelectorAll('.game-card').forEach(function(c){
            c.style.display = (f === 'all' || c.dataset.difficulty === f) ? '' : 'none';
        });
    }
});
</script>
</body>
</html>