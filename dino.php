<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'dino', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.dino", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.dino", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Динозаврик | DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#gameCanvas { display: block; margin: 0 auto; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link"><?= $site_name ?></a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
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
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container"><div class="game-wrapper">
<h1>🦖 Динозаврик</h1>
<div class="game-info-bar">
<div class="game-info-item"><span class="lbl">Счет</span><span class="val" id="scoreDisplay">0</span></div>
<div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
<div class="game-info-item"><span class="lbl">Скорость</span><span class="val" id="speedDisplay">1</span></div>
</div>
<div class="game-area"><canvas id="gameCanvas" width="600" height="250"></canvas></div>
<div class="game-controls"><button class="btn" onclick="startGame()">🔄 Старт / Прыжок</button></div>
</div></div>
<footer><p>DonateCraft | Наслаждайся классической игрой про птичку</p></footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const speedDisplay = document.getElementById('speedDisplay');

const GROUND_Y = 210;
const DINO_W = 36, DINO_H = 44;

let dino = { x: 60, y: GROUND_Y - DINO_H, w: DINO_W, h: DINO_H, vy: 0, jumping: false };
let obstacles = [];
let score = 0;
let distance = 0;
let obstaclesPassed = 0;
let speed = 4;
let running = false;
let gameOver = false;
let frame = 0;
let spawnTimer = 0;
let minSpawnInterval = 60;

const GRAVITY = 0.6;
const JUMP_FORCE = -11;

function startGame() {
    dino.y = GROUND_Y - DINO_H;
    dino.vy = 0;
    dino.jumping = false;
    obstacles = [];
    score = 0;
    distance = 0;
    obstaclesPassed = 0;
    speed = 4;
    frame = 0;
    spawnTimer = 0;
    gameOver = false;
    running = true;
    scoreDisplay.textContent = '0';
    speedDisplay.textContent = '1';
}

function jump() {
    if (!running || gameOver) return;
    if (!dino.jumping) {
        dino.vy = JUMP_FORCE;
        dino.jumping = true;
    }
}

function spawnObstacle() {
    const types = [
        { w: 16, h: 32 },
        { w: 12, h: 40 },
        { w: 24, h: 28 },
        { w: 18, h: 36 }
    ];
    const type = types[Math.floor(Math.random() * types.length)];
    obstacles.push({
        x: canvas.width,
        w: type.w,
        h: type.h,
        y: GROUND_Y - type.h,
        passed: false
    });
}

function endGame() {
    gameOver = true;
    running = false;
    const finalScore = Math.floor(distance / 10) + obstaclesPassed * 50;
    score = finalScore;
    scoreDisplay.textContent = finalScore;
    const formData = new FormData();
    formData.append('score', finalScore);
    fetch('dino.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function update() {
    if (!running || gameOver) return;
    frame++;

    dino.vy += GRAVITY;
    dino.y += dino.vy;
    if (dino.y >= GROUND_Y - DINO_H) {
        dino.y = GROUND_Y - DINO_H;
        dino.vy = 0;
        dino.jumping = false;
    }

    distance++;
    if (distance % 100 === 0) {
        speed = 4 + Math.floor(distance / 300) * 0.5;
        if (speed > 12) speed = 12;
        speedDisplay.textContent = Math.floor(speed - 3);
        minSpawnInterval = Math.max(30, 60 - Math.floor(distance / 200) * 3);
    }

    spawnTimer++;
    if (spawnTimer >= minSpawnInterval + Math.floor(Math.random() * 30)) {
        spawnObstacle();
        spawnTimer = 0;
    }

    for (let i = obstacles.length - 1; i >= 0; i--) {
        const obs = obstacles[i];
        obs.x -= speed;
        if (obs.x + obs.w < 0) {
            if (!obs.passed) {
                obs.passed = true;
                obstaclesPassed++;
            }
            obstacles.splice(i, 1);
            continue;
        }
        if (!obs.passed && obs.x + obs.w < dino.x) {
            obs.passed = true;
            obstaclesPassed++;
        }

        if (dino.x + dino.w - 6 > obs.x + 2 && dino.x + 6 < obs.x + obs.w - 2 &&
            dino.y + dino.h > obs.y + 2 && dino.y < obs.y + obs.h - 2) {
            endGame();
            return;
        }
    }
}

function draw() {
    ctx.fillStyle = '#1a0a00';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    ctx.fillStyle = '#2d1b00';
    ctx.fillRect(0, GROUND_Y, canvas.width, 4);
    ctx.fillStyle = '#3d2b10';
    for (let x = 0; x < canvas.width; x += 15) {
        ctx.fillRect(x, GROUND_Y + 6, 6, 2);
    }

    ctx.fillStyle = '#44bb44';
    ctx.fillRect(dino.x, dino.y, dino.w, dino.h);
    ctx.fillStyle = '#33aa33';
    ctx.fillRect(dino.x + 4, dino.y + 4, dino.w - 8, 6);
    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.arc(dino.x + dino.w - 8, dino.y + 10, 5, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#222';
    ctx.beginPath();
    ctx.arc(dino.x + dino.w - 6, dino.y + 10, 2, 0, Math.PI * 2);
    ctx.fill();

    ctx.fillStyle = '#8B4513';
    for (const obs of obstacles) {
        ctx.fillRect(obs.x, obs.y, obs.w, obs.h);
        ctx.fillStyle = '#A0522D';
        ctx.fillRect(obs.x + 2, obs.y + 4, obs.w - 4, 4);
        ctx.fillStyle = '#8B4513';
    }

    const distScore = Math.floor(distance / 10) + obstaclesPassed * 50;
    if (running && !gameOver) {
        scoreDisplay.textContent = distScore;
    }

    if (!running && !gameOver) {
        ctx.fillStyle = '#ffaa33';
        ctx.font = '18px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Нажми пробел для старта', canvas.width / 2, 80);
    }

    if (gameOver) {
        ctx.fillStyle = 'rgba(0,0,0,0.6)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#ff4444';
        ctx.font = '28px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('💀 Игра окончена!', canvas.width / 2, canvas.height / 2 - 20);
        ctx.fillStyle = '#ffaa33';
        ctx.font = '18px Inter, sans-serif';
        ctx.fillText('Счет: ' + score, canvas.width / 2, canvas.height / 2 + 15);
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
        if (!running && !gameOver) { startGame(); return; }
        jump();
    }
});

canvas.addEventListener('click', () => {
    if (!running && !gameOver) { startGame(); return; }
    jump();
});

gameLoop();
</script></body></html>