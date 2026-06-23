<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Память</title>
<link rel="stylesheet" href="style.css">
<style>
.memory-grid { display: grid; grid-template-columns: repeat(4, 90px); gap: 8px; justify-content: center; margin: 20px auto; }
.memory-card {
    width: 90px; height: 90px; background: linear-gradient(135deg, #1a3a1a, #0a2a0a);
    border: 2px solid rgba(0,170,0,0.25); border-radius: 12px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; font-size: 32px;
    transition: all 0.35s ease; user-select: none; position: relative;
    transform-style: preserve-3d;
}
.memory-card:hover { border-color: rgba(0,170,0,0.5); transform: translateY(-2px); box-shadow: 0 4px 20px rgba(0,170,0,0.15); }
.memory-card.flipped { background: rgba(22,33,62,0.9); border-color: rgba(0,255,0,0.5); transform: rotateY(0); }
.memory-card.matched { background: rgba(0,170,0,0.15); border-color: #00ff00; cursor: default; transform: scale(0.95); animation: matchPulse 0.5s ease; }
.memory-card.matched:hover { transform: scale(0.95); }
.memory-card .card-back { font-size: 28px; opacity: 0.6; }
@keyframes matchPulse { 0% { transform: scale(0.95); } 50% { transform: scale(1.1); } 100% { transform: scale(0.95); } }
@keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
.memory-card.wrong { animation: shake 0.4s ease; border-color: #ff4444; }
</style>
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

</div>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>🃏 Память</h1>
        <p style="color:#888;margin-bottom:16px;">Найди все пары одинаковых карт!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Ходы</span><span class="val" id="movesDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Найдено пар</span><span class="val" id="pairsDisplay">0 / 8</span></div>
            <div class="game-info-item"><span class="lbl">Счет</span><span class="val" id="scoreDisplay">0</span></div>
        </div>

        <div class="memory-grid" id="memoryGrid"></div>

        <div class="game-controls">
            <button id="newGameBtn" class="btn">🔄 Новая игра</button>
        </div>

        <div id="result" style="font-size:18px;font-weight:600;min-height:30px;"></div>
    </div>
</div>

<script>
const grid = document.getElementById('memoryGrid');
const movesDisplay = document.getElementById('movesDisplay');
const pairsDisplay = document.getElementById('pairsDisplay');
const scoreDisplay = document.getElementById('scoreDisplay');
const newGameBtn = document.getElementById('newGameBtn');
const resultDiv = document.getElementById('result');

const emojis = ['🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼'];
let cards, flippedIndices, matchedPairs, moves, locked, saved;

function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
}

function newGame() {
    let deck = shuffle([...emojis, ...emojis]);
    cards = deck.map((emoji, idx) => ({ emoji, matched: false, id: idx }));
    flippedIndices = [];
    matchedPairs = 0;
    moves = 0;
    locked = false;
    saved = false;
    resultDiv.innerHTML = '';
    renderGrid();
    updateStats();
}

function renderGrid() {
    grid.innerHTML = '';
    cards.forEach((card, i) => {
        const div = document.createElement('div');
        div.className = 'memory-card';
        div.dataset.index = i;
        div.innerHTML = '<span class="card-back">❓</span>';
        div.addEventListener('click', () => flipCard(i));
        grid.appendChild(div);
    });
}

function flipCard(index) {
    if (locked) return;
    if (cards[index].matched) return;
    if (flippedIndices.includes(index)) return;
    if (flippedIndices.length >= 2) return;

    flippedIndices.push(index);
    const el = grid.children[index];
    el.classList.add('flipped');
    el.innerHTML = cards[index].emoji;

    if (flippedIndices.length === 2) {
        locked = true;
        moves++;
        checkMatch();
    }
}

function checkMatch() {
    const [i1, i2] = flippedIndices;
    const c1 = cards[i1], c2 = cards[i2];

    if (c1.emoji === c2.emoji) {
        c1.matched = true;
        c2.matched = true;
        matchedPairs++;
        setTimeout(() => {
            grid.children[i1].classList.add('matched');
            grid.children[i2].classList.add('matched');
            flippedIndices = [];
            locked = false;
            updateStats();
            if (matchedPairs === 8) {
                gameOver();
            }
        }, 400);
    } else {
        setTimeout(() => {
            grid.children[i1].classList.add('wrong');
            grid.children[i2].classList.add('wrong');
            setTimeout(() => {
                grid.children[i1].classList.remove('flipped', 'wrong');
                grid.children[i2].classList.remove('flipped', 'wrong');
                grid.children[i1].innerHTML = '<span class="card-back">❓</span>';
                grid.children[i2].innerHTML = '<span class="card-back">❓</span>';
                flippedIndices = [];
                locked = false;
                updateStats();
            }, 400);
        }, 500);
    }
}

function updateStats() {
    movesDisplay.textContent = moves;
    pairsDisplay.textContent = matchedPairs + ' / 8';
    let score = Math.max(0, matchedPairs * 50 - moves * 2);
    scoreDisplay.textContent = score;
}

function gameOver() {
    let score = Math.max(0, matchedPairs * 50 - moves * 2);
    if (!saved) {
        saved = true;
        fetch('api.php?action=save_score&game=memory&level=1&points=' + score)
            .then(r => r.text())
            .then(t => {
                resultDiv.innerHTML = '🎉 Все пары найдены! +<strong style="color:#ffd700;">' + score + '</strong> поинтов начислено';
            })
            .catch(() => {
                resultDiv.innerHTML = '🎉 Все пары найдены! ❌ Ошибка сохранения.';
            });
    }
}

newGameBtn.addEventListener('click', newGame);

newGame();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>