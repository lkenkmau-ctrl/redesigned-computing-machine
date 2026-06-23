<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'breakout', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.breakout", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.breakout", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>�������� � DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#gameCanvas { cursor: none; display: block; margin: 0 auto; }
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
                    <a href="pacman.php">👾 Пакман</a></div><
                <a href="games.php" class="btn btn-sm">🎮 Играть</a>/div><a href="donate.php" class="btn btn-sm">💰 Донат</a><a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>?? ��������</h1>
<div class="game-info-bar">
<div class="game-info-item"><span class="lbl">����</span><span class="val" id="scoreDisplay">0</span></div>
<div class="game-info-item"><span class="lbl">������</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
<div class="game-info-item"><span class="lbl">�����</span><span class="val" id="livesDisplay">??????</span></div>
</div>
<div class="game-area"><canvas id="gameCanvas" width="500" height="400"></canvas></div>
<div class="game-controls"><button class="btn" onclick="startGame()">?? ����� ����</button></div>
</div></div>
<footer><p>DonateCraft � ����������� �������� ������ �� ����-����</p></footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const livesDisplay = document.getElementById('livesDisplay');

const COLS = 10, ROWS = 5;
const BRICK_W = 46, BRICK_H = 18, BRICK_GAP = 4;
const PADDLE_W = 80, PADDLE_H = 12;
const BALL_R = 7;

let paddle = { x: (canvas.width - PADDLE_W) / 2, y: canvas.height - 30 };
let ball = { x: canvas.width / 2, y: canvas.height - 45, dx: 3, dy: -3, r: BALL_R };
let bricks = [];
let score = 0;
let lives = 3;
let running = false;
let gameOver = false;
let mouseX = canvas.width / 2;

const rowColors = ['#ff4444', '#ff8800', '#ffcc00', '#44cc44', '#4488ff'];

function initBricks() {
    bricks = [];
    for (let r = 0; r < ROWS; r++) {
        for (let c = 0; c < COLS; c++) {
            bricks.push({
                x: c * (BRICK_W + BRICK_GAP) + BRICK_GAP,
                y: r * (BRICK_H + BRICK_GAP) + BRICK_GAP + 10,
                w: BRICK_W, h: BRICK_H,
                alive: true,
                color: rowColors[r]
            });
        }
    }
}

function startGame() {
    paddle.x = (canvas.width - PADDLE_W) / 2;
    ball.x = canvas.width / 2;
    ball.y = canvas.height - 45;
    ball.dx = 3 * (Math.random() > 0.5 ? 1 : -1);
    ball.dy = -3;
    score = 0;
    lives = 3;
    gameOver = false;
    running = true;
    scoreDisplay.textContent = '0';
    updateLives();
    initBricks();
}

function resetGame() { startGame(); }

function updateLives() {
    livesDisplay.textContent = '??'.repeat(lives);
}

function endGame() {
    gameOver = true;
    running = false;
    const formData = new FormData();
    formData.append('score', score);
    fetch('breakout.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function update() {
    if (!running) return;

    paddle.x = mouseX - PADDLE_W / 2;
    if (paddle.x < 0) paddle.x = 0;
    if (paddle.x + PADDLE_W > canvas.width) paddle.x = canvas.width - PADDLE_W;

    ball.x += ball.dx;
    ball.y += ball.dy;

    if (ball.x - ball.r < 0) { ball.x = ball.r; ball.dx = -ball.dx; }
    if (ball.x + ball.r > canvas.width) { ball.x = canvas.width - ball.r; ball.dx = -ball.dx; }
    if (ball.y - ball.r < 0) { ball.y = ball.r; ball.dy = -ball.dy; }

    if (ball.y + ball.r > canvas.height) {
        lives--;
        updateLives();
        if (lives <= 0) { endGame(); return; }
        ball.x = canvas.width / 2;
        ball.y = canvas.height - 45;
        ball.dx = 3 * (Math.random() > 0.5 ? 1 : -1);
        ball.dy = -3;
        return;
    }

    if (ball.y + ball.r >= paddle.y && ball.y - ball.r <= paddle.y + PADDLE_H &&
        ball.x + ball.r >= paddle.x && ball.x - ball.r <= paddle.x + PADDLE_W) {
        ball.dy = -Math.abs(ball.dy);
        let hit = (ball.x - paddle.x) / PADDLE_W;
        ball.dx = 3 * (hit - 0.5) * 2;
        ball.y = paddle.y - ball.r;
    }

    let allDead = true;
    for (const b of bricks) {
        if (!b.alive) continue;
        allDead = false;
        if (ball.x + ball.r > b.x && ball.x - ball.r < b.x + b.w &&
            ball.y + ball.r > b.y && ball.y - ball.r < b.y + b.h) {
            b.alive = false;
            score += 10;
            scoreDisplay.textContent = score;

            let overlapX = (ball.x - b.x) / b.w;
            let overlapY = (ball.y - b.y) / b.h;
            if (overlapX > overlapY) {
                ball.dy = -ball.dy;
            } else {
                ball.dx = -ball.dx;
            }
        }
    }
    if (allDead) { endGame(); return; }
}

function draw() {
    ctx.fillStyle = '#1a0a00';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    for (const b of bricks) {
        if (!b.alive) continue;
        ctx.fillStyle = b.color;
        ctx.beginPath();
        ctx.roundRect(b.x, b.y, b.w, b.h, 3);
        ctx.fill();
        ctx.fillStyle = 'rgba(255,255,255,0.15)';
        ctx.beginPath();
        ctx.roundRect(b.x + 2, b.y + 2, b.w - 4, b.h / 2 - 2, 2);
        ctx.fill();
    }

    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.roundRect(paddle.x, paddle.y, PADDLE_W, PADDLE_H, 6);
    ctx.fill();
    ctx.fillStyle = 'rgba(255,255,255,0.3)';
    ctx.beginPath();
    ctx.roundRect(paddle.x + 6, paddle.y + 2, PADDLE_W - 12, 4, 2);
    ctx.fill();

    ctx.fillStyle = '#ffcc00';
    ctx.beginPath();
    ctx.arc(ball.x, ball.y, ball.r, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.beginPath();
    ctx.arc(ball.x - 2, ball.y - 2, 2, 0, Math.PI * 2);
    ctx.fill();

    if (gameOver) {
        ctx.fillStyle = 'rgba(0,0,0,0.6)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#ffcc00';
        ctx.font = '28px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('���� ��������!', canvas.width / 2, canvas.height / 2 - 10);
        ctx.fillStyle = '#ffaa33';
        ctx.font = '18px Inter, sans-serif';
        ctx.fillText('����: ' + score, canvas.width / 2, canvas.height / 2 + 25);
    } else if (!running) {
        ctx.fillStyle = '#ffaa33';
        ctx.font = '18px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('����� "����� ����"', canvas.width / 2, canvas.height / 2);
    }
}

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

canvas.addEventListener('mousemove', e => {
    const rect = canvas.getBoundingClientRect();
    mouseX = (e.clientX - rect.left) * (canvas.width / rect.width);
});

if (!ctx.roundRect) {
    CanvasRenderingContext2D.prototype.roundRect = function(x, y, w, h, r) {
        if (r > w / 2) r = w / 2;
        if (r > h / 2) r = h / 2;
        this.moveTo(x + r, y);
        this.lineTo(x + w - r, y);
        this.quadraticCurveTo(x + w, y, x + w, y + r);
        this.lineTo(x + w, y + h - r);
        this.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        this.lineTo(x + r, y + h);
        this.quadraticCurveTo(x, y + h, x, y + h - r);
        this.lineTo(x, y + r);
        this.quadraticCurveTo(x, y, x + r, y);
        this.closePath();
    };
}

gameLoop();
</script></body></html>
