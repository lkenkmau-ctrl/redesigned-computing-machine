<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Тетрис</title>
<link rel="stylesheet" href="style.css">
<style>
#gameCanvas { border: 2px solid rgba(68,136,255,0.3); background: #0a0a15; border-radius: 8px; }
#nextCanvas { border: 1px solid rgba(255,255,255,0.1); background: #0a0a15; border-radius: 6px; }
.controls-hint { display: flex; gap: 4px; justify-content: center; margin: 12px 0; flex-wrap: wrap; }
.key { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; padding: 6px 14px; font-size: 13px; color: #888; font-family: monospace; }
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
        <h1>🧊 Тетрис</h1>
        <p style="color:#888;margin-bottom:16px;">Расставляй блоки, заполняй ряды и ставь рекорды! Убирай по 4 ряда за раз.</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Уровень</span><span class="val" id="levelDisplay">1</span></div>
            <div class="game-info-item"><span class="lbl">Линии</span><span class="val" id="linesDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Очки</span><span class="val" id="pointsDisplay">0</span></div>
        </div>

        <div class="game-area">
            <canvas id="gameCanvas" width="300" height="600"></canvas>
            <div class="game-side">
                <p style="color:#888;margin-bottom:8px;">Следующая:</p>
                <canvas id="nextCanvas" width="120" height="120"></canvas>
            </div>
        </div>

        <div class="controls-hint">
            <span class="key">←</span>
            <span class="key">↓</span>
            <span class="key">→</span>
            <span class="key">↑ / W</span>
            <span class="key" style="background:rgba(255,215,0,0.1);border-color:#ffd700;">Пробел</span>
        </div>

        <div class="game-controls">
            <button id="startBtn" class="btn btn-blue" style="min-width:140px;">▶ Старт</button>
            <a href="profile.php" class="btn btn-outline">Выйти</a>
        </div>

        <div id="result" style="font-size:18px;font-weight:600;min-height:30px;"></div>

        <div style="margin-top:16px;background:rgba(22,33,62,0.5);border-radius:10px;padding:16px;text-align:left;font-size:13px;color:#888;">
            <strong style="color:#aaa;">Правила:</strong> каждые 10 очищенных линий = новый уровень. На каждом уровне скорость растет. <strong style="color:#4488ff;">+100 поинтов</strong> за линию. Удачи! Убирай по 4 ряда за раз.
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const nextCanvas = document.getElementById('nextCanvas');
const nextCtx = nextCanvas.getContext('2d');
const levelDisplay = document.getElementById('levelDisplay');
const linesDisplay = document.getElementById('linesDisplay');
const pointsDisplay = document.getElementById('pointsDisplay');
const startBtn = document.getElementById('startBtn');
const resultDiv = document.getElementById('result');

const COLS = 10, ROWS = 20;
const BLOCK = 30;
const PIECES = [
    [[1,1,1,1]],
    [[1,1],[1,1]],
    [[0,1,0],[1,1,1]],
    [[1,0,0],[1,1,1]],
    [[0,0,1],[1,1,1]],
    [[0,1,1],[1,1,0]],
    [[1,1,0],[0,1,1]]
];
const COLORS = ['#00f0f0','#f0f000','#a000f0','#0000f0','#f0a000','#00f000','#f00000'];

let board, currentPiece, currentX, currentY, currentColor, nextPiece, nextColor;
let level, lines, score, gameRunning, gameLoop, saved, dropTimer, dropInterval;

function wrapX(x) {
    return ((x % COLS) + COLS) % COLS;
}

function init() {
    board = Array.from({length: ROWS}, () => Array(COLS).fill(0));
    nextPiece = PIECES[Math.floor(Math.random() * PIECES.length)];
    nextColor = Math.floor(Math.random() * COLORS.length);
    level = 1; lines = 0; score = 0; saved = false;
    dropTimer = 0; dropInterval = 1000;
    spawnPiece();
    updateDisplay();
    draw();
    drawNext();
}

function spawnPiece() {
    currentPiece = nextPiece;
    currentColor = nextColor;
    nextPiece = PIECES[Math.floor(Math.random() * PIECES.length)];
    nextColor = Math.floor(Math.random() * COLORS.length);
    currentX = Math.floor((COLS - currentPiece[0].length) / 2);
    currentY = 0;
    if (collides(currentX, currentY, currentPiece)) {
        gameOver();
        return;
    }
    drawNext();
}

function collides(px, py, piece) {
    for (let r = 0; r < piece.length; r++) {
        for (let c = 0; c < piece[r].length; c++) {
            if (!piece[r][c]) continue;
            let nx = wrapX(px + c);
            let ny = py + r;
            if (ny >= ROWS) return true;
            if (ny >= 0 && board[ny][nx]) return true;
        }
    }
    return false;
}

function lockPiece() {
    for (let r = 0; r < currentPiece.length; r++) {
        for (let c = 0; c < currentPiece[r].length; c++) {
            if (!currentPiece[r][c]) continue;
            let nx = wrapX(currentX + c);
            let ny = currentY + r;
            if (ny >= 0 && ny < ROWS) board[ny][nx] = currentColor + 1;
        }
    }
    clearLines();
    spawnPiece();
}

function clearLines() {
    let cleared = 0;
    for (let r = ROWS - 1; r >= 0; r--) {
        if (board[r].every(c => c !== 0)) {
            board.splice(r, 1);
            board.unshift(Array(COLS).fill(0));
            cleared++;
            r++;
        }
    }
    if (cleared > 0) {
        lines += cleared;
        score += cleared * 100;
        let newLevel = Math.floor(lines / 10) + 1;
        if (newLevel > level) { level = newLevel; dropInterval = Math.max(100, 1000 - (level - 1) * 80); }
        updateDisplay();
    }
}

function updateDisplay() {
    levelDisplay.textContent = level;
    linesDisplay.textContent = lines;
    pointsDisplay.textContent = '+' + score;
}

function draw() {
    ctx.fillStyle = '#0a0a15';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    for (let r = 0; r < ROWS; r++) {
        for (let c = 0; c < COLS; c++) {
            if (board[r][c]) {
                ctx.fillStyle = COLORS[board[r][c] - 1];
                ctx.shadowColor = COLORS[board[r][c] - 1];
                ctx.shadowBlur = 4;
                ctx.fillRect(c * BLOCK + 1, r * BLOCK + 1, BLOCK - 2, BLOCK - 2);
                ctx.shadowBlur = 0;
            } else {
                ctx.fillStyle = 'rgba(255,255,255,0.02)';
                ctx.fillRect(c * BLOCK + 1, r * BLOCK + 1, BLOCK - 2, BLOCK - 2);
            }
        }
    }

    for (let r = 0; r < currentPiece.length; r++) {
        for (let c = 0; c < currentPiece[r].length; c++) {
            if (!currentPiece[r][c]) continue;
            let nx = wrapX(currentX + c);
            let ny = currentY + r;
            if (ny < 0) continue;
            let drawX = ((nx - currentX % COLS + COLS) % COLS) * BLOCK;
            ctx.fillStyle = COLORS[currentColor];
            ctx.shadowColor = COLORS[currentColor];
            ctx.shadowBlur = 6;
            ctx.fillRect(nx * BLOCK + 1, ny * BLOCK + 1, BLOCK - 2, BLOCK - 2);
            ctx.shadowBlur = 0;
        }
    }
}

function drawNext() {
    nextCtx.fillStyle = '#0a0a15';
    nextCtx.fillRect(0, 0, 120, 120);
    const offX = (120 - nextPiece[0].length * 30) / 2;
    const offY = (120 - nextPiece.length * 30) / 2;
    for (let r = 0; r < nextPiece.length; r++) {
        for (let c = 0; c < nextPiece[r].length; c++) {
            if (nextPiece[r][c]) {
                nextCtx.fillStyle = COLORS[nextColor];
                nextCtx.shadowColor = COLORS[nextColor];
                nextCtx.shadowBlur = 4;
                nextCtx.fillRect(offX + c * 30 + 1, offY + r * 30 + 1, 28, 28);
                nextCtx.shadowBlur = 0;
            }
        }
    }
}

function move(dx, dy) {
    if (!gameRunning) return;
    let newX = currentX + dx;
    let newY = currentY + dy;
    if (!collides(newX, newY, currentPiece)) {
        currentX = newX;
        currentY = newY;
        draw();
    } else if (dy === 1) {
        lockPiece();
        draw();
    }
}

function rotate() {
    if (!gameRunning) return;
    let rotated = currentPiece[0].map((_, i) => currentPiece.map(r => r[i]).reverse());
    if (!collides(currentX, currentY, rotated)) {
        currentPiece = rotated;
        draw();
    }
}

function gameOver() {
    gameRunning = false;
    if (gameLoop) { clearInterval(gameLoop); gameLoop = null; }
    if (!saved) {
        saved = true;
        fetch('api.php?action=save_score&game=tetris&level=' + level + '&points=' + score)
            .then(r => r.text())
            .then(t => { resultDiv.innerHTML = '💀 Игра окончена! Уровень: <strong style="color:#4488ff;">' + level + '</strong> | +<strong style="color:#ffd700;">' + score + '</strong> поинтов начислено'; })
            .catch(() => { resultDiv.innerHTML = '❌ Игра окончена! Ошибка сохранения.'; });
    }
    startBtn.textContent = '🔄 Заново';
}

function updateTetris() {
    if (!gameRunning) return;
    dropTimer += dropInterval * 0.05;
    if (dropTimer >= dropInterval) { dropTimer = 0; move(0, 1); }
}

document.addEventListener('keydown', e => {
    if (!gameRunning) return;
    switch(e.key) {
        case 'ArrowLeft': case 'a': case 'A': move(-1, 0); break;
        case 'ArrowRight': case 'd': case 'D': move(1, 0); break;
        case 'ArrowDown': case 's': case 'S': move(0, 1); break;
        case 'ArrowUp': case 'w': case 'W': rotate(); break;
        case ' ': while(!collides(currentX, currentY + 1, currentPiece)) { currentY++; } lockPiece(); draw(); break;
    }
});

startBtn.addEventListener('click', () => {
    if (gameLoop) { clearInterval(gameLoop); gameLoop = null; }
    init();
    gameRunning = true;
    startBtn.textContent = '⏳ Игра...';
    resultDiv.innerHTML = '';
    gameLoop = setInterval(updateTetris, 50);
});

init();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>