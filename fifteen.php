<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'fifteen', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.fifteen", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.fifteen", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Пятнашки</title>
<link rel="stylesheet" href="style.css">
<style>
#fifteenCanvas { border: 2px solid rgba(255,136,0,0.25); background: #1a0a00; border-radius: 8px; cursor: pointer; }
.game-status { font-size: 16px; min-height: 24px; margin: 10px 0; color: #ffaa33; }
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
                    <a href="pacman.php">👾 Пакман</a>
                </div>
</div>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>🧩 Пятнашки</h1>
        <p style="color:#888;margin-bottom:16px;">Собери все числа по порядку от 1 до 15!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Ходы</span><span class="val" id="movesDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
        </div>

        <div class="game-status" id="statusDisplay">Кликай на плитки рядом с пустой клеткой</div>

        <div class="game-area">
            <canvas id="fifteenCanvas" width="400" height="400"></canvas>
        </div>

        <div class="game-controls">
            <button class="btn" onclick="resetGame()" style="min-width:140px;">🔄 Новая игра</button>
            <a href="profile.php" class="btn btn-outline">Выйти</a>
        </div>

        <div style="margin-top:16px;background:rgba(22,33,62,0.5);border-radius:10px;padding:16px;text-align:left;font-size:13px;color:#888;">
            <strong style="color:#aaa;">Правила:</strong> Расставь плитки с числами от 1 до 15 по порядку. Кликай на плитку рядом с пустой клеткой, чтобы её переместить. Меньше ходов = выше счёт! Удачи!
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('fifteenCanvas');
const ctx = canvas.getContext('2d');
const movesDisplay = document.getElementById('movesDisplay');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const statusDisplay = document.getElementById('statusDisplay');

const SIZE = 4, TILE_SIZE = 100;
let tiles, emptyPos, moves, gameOver, saved;

function initSolved() {
    tiles = [];
    for (let i = 0; i < SIZE * SIZE; i++) tiles.push(i);
    emptyPos = SIZE * SIZE - 1;
    tiles[emptyPos] = 0;
}

function shuffleBoard() {
    const dirs = [[0,1],[0,-1],[1,0],[-1,0]];
    for (let i = 0; i < 200; i++) {
        const r = Math.floor(Math.random() * 4);
        const nr = Math.floor(emptyPos / SIZE) + dirs[r][0];
        const nc = (emptyPos % SIZE) + dirs[r][1];
        if (nr >= 0 && nr < SIZE && nc >= 0 && nc < SIZE) {
            const ni = nr * SIZE + nc;
            tiles[emptyPos] = tiles[ni];
            tiles[ni] = 0;
            emptyPos = ni;
        }
    }
}

function resetGame() {
    initSolved();
    shuffleBoard();
    moves = 0;
    gameOver = false;
    saved = false;
    scoreDisplay.textContent = '0';
    movesDisplay.textContent = '0';
    statusDisplay.textContent = 'Кликай на плитки рядом с пустой клеткой';
    draw();
}

function clickTile(row, col) {
    if (gameOver) return;
    const dirs = [[0,1],[0,-1],[1,0],[-1,0]];
    const er = Math.floor(emptyPos / SIZE);
    const ec = emptyPos % SIZE;
    for (const [dr, dc] of dirs) {
        if (row + dr === er && col + dc === ec) {
            const ni = row * SIZE + col;
            tiles[emptyPos] = tiles[ni];
            tiles[ni] = 0;
            emptyPos = ni;
            moves++;
            movesDisplay.textContent = moves;
            draw();
            if (checkWin()) endGame();
            return;
        }
    }
}

function checkWin() {
    for (let i = 0; i < SIZE * SIZE - 1; i++) {
        if (tiles[i] !== i + 1) return false;
    }
    return tiles[SIZE * SIZE - 1] === 0;
}

function endGame() {
    gameOver = true;
    const score = Math.max(0, 500 - moves * 5);
    scoreDisplay.textContent = score;
    statusDisplay.textContent = `🎉 Победа! ${moves} ходов, счёт: ${score}`;
    if (!saved) {
        saved = true;
        const formData = new FormData();
        formData.append('score', score);
        fetch('fifteen.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
            .catch(() => {});
    }
}

function draw() {
    ctx.fillStyle = '#1a0a00'; ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#2d1b00';
    ctx.fillRect(0, 0, SIZE * TILE_SIZE, SIZE * TILE_SIZE);
    for (let i = 0; i < SIZE * SIZE; i++) {
        const val = tiles[i];
        if (val === 0) continue;
        const row = Math.floor(i / SIZE), col = i % SIZE;
        const x = col * TILE_SIZE, y = row * TILE_SIZE;
        const grad = ctx.createLinearGradient(x, y, x + TILE_SIZE, y + TILE_SIZE);
        grad.addColorStop(0, '#ff8800');
        grad.addColorStop(1, '#cc6600');
        ctx.fillStyle = grad;
        ctx.shadowColor = 'rgba(255,136,0,0.3)';
        ctx.shadowBlur = 8;
        ctx.fillRect(x + 2, y + 2, TILE_SIZE - 4, TILE_SIZE - 4);
        ctx.shadowBlur = 0;
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 28px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(val, x + TILE_SIZE / 2, y + TILE_SIZE / 2);
    }
}

canvas.addEventListener('click', e => {
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const col = Math.floor(x / TILE_SIZE);
    const row = Math.floor(y / TILE_SIZE);
    if (row >= 0 && row < SIZE && col >= 0 && col < SIZE) clickTile(row, col);
});

resetGame();
</script>
</body>
</html>
