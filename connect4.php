<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'connect4', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.connect4", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.connect4", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>4 в ряд</title>
<link rel="stylesheet" href="style.css">
<style>
#connectCanvas { border: 2px solid rgba(255,136,0,0.25); background: #1a0a00; border-radius: 8px; cursor: pointer; }
.game-status { font-size: 18px; font-weight: 600; min-height: 30px; margin: 10px 0; color: #ffaa33; }
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
                <a href="games.php" class="btn btn-sm">🎮 Играть</a>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>🔴 4 в ряд</h1>
        <p style="color:#888;margin-bottom:16px;">Собери 4 фишки в ряд раньше компьютера!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
        </div>

        <div class="game-status" id="statusDisplay">Твой ход — нажми на колонку</div>

        <div class="game-area">
            <canvas id="connectCanvas" width="490" height="420"></canvas>
        </div>

        <div class="game-controls">
            <button class="btn" onclick="resetGame()" style="min-width:140px;">🔄 Новая игра</button>
            <a href="profile.php" class="btn btn-outline">Выйти</a>
        </div>

        <div style="margin-top:16px;background:rgba(22,33,62,0.5);border-radius:10px;padding:16px;text-align:left;font-size:13px;color:#888;">
            <strong style="color:#aaa;">Правила:</strong> Кликай на колонку, чтобы бросить красную фишку. Собери 4 в ряд по горизонтали, вертикали или диагонали. Победа = +200 очков. Удачи!
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('connectCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const statusDisplay = document.getElementById('statusDisplay');

const COLS = 7, ROWS = 6, SIZE = 60, RADIUS = 24;
let board, currentPlayer, gameOver, score, saved;

function initBoard() {
    board = Array.from({length: COLS}, () => Array(ROWS).fill(null));
    currentPlayer = 'red';
    gameOver = false;
    score = 0;
    saved = false;
    statusDisplay.textContent = 'Твой ход — нажми на колонку';
    scoreDisplay.textContent = '0';
}

function resetGame() { initBoard(); draw(); }

function dropPiece(col) {
    if (gameOver || currentPlayer !== 'red') return false;
    for (let r = ROWS - 1; r >= 0; r--) {
        if (board[col][r] === null) {
            board[col][r] = 'red';
            if (checkWin(col, r, 'red')) { endGame('win'); return true; }
            if (isFull()) { endGame('draw'); return true; }
            currentPlayer = 'yellow';
            statusDisplay.textContent = 'Бот думает...';
            draw();
            setTimeout(aiMove, 400);
            return true;
        }
    }
    return false;
}

function aiMove() {
    if (gameOver) return;
    let valid = [];
    for (let c = 0; c < COLS; c++) { if (board[c][0] === null) valid.push(c); }
    if (valid.length === 0) return;
    const col = valid[Math.floor(Math.random() * valid.length)];
    for (let r = ROWS - 1; r >= 0; r--) {
        if (board[col][r] === null) {
            board[col][r] = 'yellow';
            if (checkWin(col, r, 'yellow')) { draw(); endGame('lose'); return; }
            if (isFull()) { draw(); endGame('draw'); return; }
            currentPlayer = 'red';
            statusDisplay.textContent = 'Твой ход — нажми на колонку';
            draw();
            return;
        }
    }
}

function isFull() {
    return board.every(col => col[0] !== null);
}

function checkWin(col, row, player) {
    const directions = [[1,0],[0,1],[1,1],[1,-1]];
    for (const [dx, dy] of directions) {
        let count = 1;
        for (let dir = -1; dir <= 1; dir += 2) {
            let c = col + dx * dir, r = row + dy * dir;
            while (c >= 0 && c < COLS && r >= 0 && r < ROWS && board[c][r] === player) {
                count++; c += dx * dir; r += dy * dir;
            }
        }
        if (count >= 4) return true;
    }
    return false;
}

function endGame(result) {
    gameOver = true;
    if (result === 'win') { score = 200; statusDisplay.textContent = '🎉 Ты выиграл! +200 очков'; }
    else if (result === 'lose') { score = 0; statusDisplay.textContent = '😵 Компьютер выиграл!'; }
    else { score = 50; statusDisplay.textContent = '🤝 Ничья! +50 очков'; }
    scoreDisplay.textContent = score;
    if (!saved) {
        saved = true;
        const formData = new FormData();
        formData.append('score', score);
        fetch('connect4.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
            .catch(() => {});
    }
}

function draw() {
    ctx.fillStyle = '#1a0a00'; ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#2d1b00';
    ctx.fillRect(10, 0, COLS * SIZE, ROWS * SIZE);
    for (let c = 0; c < COLS; c++) {
        for (let r = 0; r < ROWS; r++) {
            const x = 10 + c * SIZE + SIZE / 2, y = r * SIZE + SIZE / 2 + 10;
            ctx.beginPath(); ctx.arc(x, y, RADIUS, 0, Math.PI * 2);
            ctx.fillStyle = '#1a0a00'; ctx.fill();
            ctx.strokeStyle = 'rgba(255,136,0,0.15)'; ctx.lineWidth = 2; ctx.stroke();
            if (board[c][r]) {
                ctx.beginPath(); ctx.arc(x, y, RADIUS - 4, 0, Math.PI * 2);
                ctx.fillStyle = board[c][r] === 'red' ? '#ff3333' : '#ffcc00';
                ctx.fill();
                ctx.shadowColor = board[c][r] === 'red' ? '#ff3333' : '#ffcc00';
                ctx.shadowBlur = 10; ctx.fill(); ctx.shadowBlur = 0;
            }
        }
    }
}

canvas.addEventListener('click', e => {
    if (gameOver || currentPlayer !== 'red') return;
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const col = Math.floor((x - 10) / SIZE);
    if (col >= 0 && col < COLS) dropPiece(col);
});

initBoard(); draw();
</script>
</body>
</html>
