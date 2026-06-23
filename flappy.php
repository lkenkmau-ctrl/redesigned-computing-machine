<?php
require_once 'config.php';
requireAuth();
$page_title = "Flappy Bird";
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', [
        'user_id' => $user_id,
        'game' => 'flappy',
        'score' => $score,
        'created_at' => date('c')
    ]);
    $bests = supabaseSelect('game_scores', [
        'select' => 'score',
        'where' => "user_id=eq.$user_id&game=eq.flappy",
        'order' => 'score.desc',
        'limit' => 1
    ]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]);
    exit;
}

$bestData = supabaseSelect('game_scores', [
    'select' => 'score',
    'where' => "user_id=eq.$user_id&game=eq.flappy",
    'order' => 'score.desc',
    'limit' => 1
]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flappy Bird | DonateCraft</title>
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

</div>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper">
        <h1>🐦 Flappy Bird</h1>
        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Счет</span><span class="val" id="scoreDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
        </div>
        <div class="game-area">
            <canvas id="gameCanvas" width="400" height="500"></canvas>
        </div>
        <div class="game-controls">
            <button class="btn" onclick="startGame()">🔄 Старт / Прыжок</button>
        </div>
    </div>
</div>
<footer>
    <p>DonateCraft | Наслаждайся классической игрой про птичку</p>
</footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');

const bird = { x: 60, y: 200, size: 14, velocity: 0, gravity: 0.5, jump: -8 };
const pipes = [];
const PIPE_W = 40;
const GAP = 150;
let score = 0;
let gameOver = false;
let gameStarted = false;
let frame = 0;
let pipeInterval = 90;

function resetGame() {
    bird.y = 200;
    bird.velocity = 0;
    pipes.length = 0;
    score = 0;
    scoreDisplay.textContent = '0';
    gameOver = false;
    frame = 0;
}

function jump() {
    if (!gameStarted || gameOver) return;
    bird.velocity = bird.jump;
}

function startGame() {
    resetGame();
    gameStarted = true;
}

function spawnPipe() {
    const minY = 50;
    const maxY = canvas.height - GAP - 50;
    const topH = Math.floor(Math.random() * (maxY - minY + 1)) + minY;
    pipes.push({ x: canvas.width, topH: topH, scored: false });
}

function update() {
    if (!gameStarted || gameOver) return;
    frame++;
    bird.velocity += bird.gravity;
    bird.y += bird.velocity;

    if (frame % pipeInterval === 0) spawnPipe();
    if (frame % pipeInterval === 10) spawnPipe();

    for (let i = pipes.length - 1; i >= 0; i--) {
        pipes[i].x -= 2;
        if (pipes[i].x + PIPE_W < 0) { pipes.splice(i, 1); continue; }
        if (!pipes[i].scored && pipes[i].x + PIPE_W < bird.x) {
            pipes[i].scored = true;
            score++;
            scoreDisplay.textContent = score;
        }
    }

    if (bird.y - bird.size <= 0 || bird.y + bird.size >= canvas.height) {
        endGame(); return;
    }
    for (const p of pipes) {
        if (bird.x + bird.size > p.x && bird.x - bird.size < p.x + PIPE_W) {
            if (bird.y - bird.size < p.topH || bird.y + bird.size > p.topH + GAP) {
                endGame(); return;
            }
        }
    }
}

function endGame() {
    gameOver = true;
    gameStarted = false;
    const formData = new FormData();
    formData.append('score', score);
    fetch('flappy.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function draw() {
    ctx.fillStyle = '#1a0a00';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.fillStyle = '#2d1b00';
    ctx.fillRect(0, canvas.height - 30, canvas.width, 30);
    ctx.strokeStyle = '#ff880033';
    ctx.lineWidth = 2;
    ctx.beginPath();
    for (let x = 0; x < canvas.width; x += 30) {
        ctx.moveTo(x, canvas.height - 30);
        ctx.lineTo(x + 15, canvas.height - 20);
        ctx.lineTo(x + 30, canvas.height - 30);
    }
    ctx.stroke();

    for (const p of pipes) {
        ctx.fillStyle = '#cc6600';
        ctx.fillRect(p.x, 0, PIPE_W, p.topH);
        ctx.fillRect(p.x, p.topH + GAP, PIPE_W, canvas.height - p.topH - GAP);
        ctx.fillStyle = '#ff8800';
        ctx.fillRect(p.x - 3, p.topH - 20, PIPE_W + 6, 20);
        ctx.fillRect(p.x - 3, p.topH + GAP, PIPE_W + 6, 20);
    }

    ctx.save();
    ctx.translate(bird.x, bird.y);
    ctx.fillStyle = '#ffcc00';
    ctx.beginPath();
    ctx.arc(0, 0, bird.size, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#ff8800';
    ctx.beginPath();
    ctx.arc(3, -3, 4, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#000';
    ctx.beginPath();
    ctx.arc(5, -4, 2, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#ff6600';
    ctx.beginPath();
    ctx.moveTo(10, 0);
    ctx.lineTo(20, -2);
    ctx.lineTo(10, 4);
    ctx.closePath();
    ctx.fill();
    ctx.restore();

    if (!gameStarted && !gameOver) {
        ctx.fillStyle = '#ffaa33';
        ctx.font = '18px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Нажми "Старт" чтобы начать', canvas.width/2, canvas.height/2 - 20);
    }
    if (gameOver) {
        ctx.fillStyle = '#ff4444';
        ctx.font = '28px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Игра окончена!', canvas.width/2, canvas.height/2);
        ctx.fillStyle = '#ffaa33';
        ctx.font = '16px Inter, sans-serif';
        ctx.fillText('Счет: ' + score, canvas.width/2, canvas.height/2 + 30);
    }
}

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

document.addEventListener('keydown', e => {
    if (e.code === 'Space') {
        e.preventDefault();
        if (!gameStarted && !gameOver) { startGame(); return; }
        jump();
    }
});

canvas.addEventListener('click', () => {
    if (!gameStarted && !gameOver) { startGame(); return; }
    jump();
});

gameLoop();
</script>
</body>
</html>