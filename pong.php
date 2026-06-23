<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'pong', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.pong", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.pong", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>���� � DonateCraft</title><link rel="stylesheet" href="style.css"><style>
canvas { border: 2px solid rgba(255,136,0,0.25); background: #0a0500; border-radius: 8px; cursor: none; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
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
<h1>?? ����</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">����</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">���������</span><span class="val" id="missDisplay">0</span></div><div class="game-info-item"><span class="lbl">������</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area"><canvas id="gameCanvas" width="600" height="400"></canvas></div>
<div id="gameMessage" style="font-size:18px;font-weight:600;min-height:28px;margin:8px 0;color:#ffaa33;"></div>
<div class="game-controls"><button class="btn" onclick="resetGame()">?? ����� ����</button></div>
</div></div>
<footer><p>DonateCraft � ����������� �������� ������ �� ����-����</p></footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const missDisplay = document.getElementById('missDisplay');
const gameMessage = document.getElementById('gameMessage');
const W = 600, H = 400;
const PADDLE_W = 12, PADDLE_H = 80;
const BALL_SIZE = 8;
const WIN_SCORE = 5;
const MAX_MISSES = 3;

let playerY, aiY, ball, playerSpeed, aiSpeed;
let playerScore = 0;
let aiScore = 0;
let gameOver = false;
let scoreSubmitted = false;
let gameRunning = false;

function resetBall() {
    ball = { x: W/2, y: H/2, dx: (Math.random() > 0.5 ? 4 : -4), dy: (Math.random() - 0.5) * 4 };
}

function resetGame() {
    playerY = H/2 - PADDLE_H/2;
    aiY = H/2 - PADDLE_H/2;
    playerScore = 0;
    aiScore = 0;
    gameOver = false;
    scoreSubmitted = false;
    gameRunning = true;
    scoreDisplay.textContent = '0';
    missDisplay.textContent = '0';
    gameMessage.textContent = '';
    playerSpeed = 5;
    aiSpeed = 4;
    resetBall();
}

function update() {
    if (!gameRunning || gameOver) return;

    ball.x += ball.dx;
    ball.y += ball.dy;

    if (ball.y - BALL_SIZE <= 0 || ball.y + BALL_SIZE >= H) ball.dy = -ball.dy;

    if (ball.x - BALL_SIZE <= PADDLE_W && ball.y >= playerY && ball.y <= playerY + PADDLE_H) {
        ball.dx = -ball.dx;
        ball.x = PADDLE_W + BALL_SIZE;
        const hitPos = (ball.y - playerY) / PADDLE_H - 0.5;
        ball.dy = hitPos * 5;
    }

    if (ball.x + BALL_SIZE >= W - PADDLE_W && ball.y >= aiY && ball.y <= aiY + PADDLE_H) {
        ball.dx = -ball.dx;
        ball.x = W - PADDLE_W - BALL_SIZE;
        const hitPos = (ball.y - aiY) / PADDLE_H - 0.5;
        ball.dy = hitPos * 5;
    }

    if (ball.x - BALL_SIZE < 0) {
        aiScore++;
        missDisplay.textContent = aiScore;
        if (aiScore >= MAX_MISSES) { gameMessage.textContent = '?? ���� ��������! �� ��������� ' + aiScore + '-' + playerScore; endGame(); return; }
        resetBall();
    }
    if (ball.x + BALL_SIZE > W) {
        playerScore++;
        scoreDisplay.textContent = playerScore;
        if (playerScore >= WIN_SCORE) { gameMessage.textContent = '?? ������! ' + playerScore + '-' + aiScore; endGame(); return; }
        resetBall();
    }

    // AI
    const targetY = ball.y - PADDLE_H/2;
    const diff = targetY - aiY;
    if (Math.abs(diff) > aiSpeed) aiY += Math.sign(diff) * aiSpeed;
    else aiY = targetY;
    aiY = Math.max(0, Math.min(H - PADDLE_H, aiY));
}

function draw() {
    ctx.fillStyle = '#0a0500';
    ctx.fillRect(0, 0, W, H);

    // Dashed center line
    ctx.strokeStyle = 'rgba(255,136,0,0.2)';
    ctx.lineWidth = 2;
    ctx.setLineDash([10, 10]);
    ctx.beginPath();
    ctx.moveTo(W/2, 0);
    ctx.lineTo(W/2, H);
    ctx.stroke();
    ctx.setLineDash([]);

    // Player paddle
    ctx.fillStyle = '#ff8800';
    ctx.shadowColor = '#ff8800';
    ctx.shadowBlur = 10;
    ctx.fillRect(4, playerY, PADDLE_W, PADDLE_H);
    ctx.shadowBlur = 0;

    // AI paddle
    ctx.fillStyle = '#4488ff';
    ctx.shadowColor = '#4488ff';
    ctx.shadowBlur = 10;
    ctx.fillRect(W - 4 - PADDLE_W, aiY, PADDLE_W, PADDLE_H);
    ctx.shadowBlur = 0;

    // Ball
    ctx.fillStyle = '#ffffff';
    ctx.shadowColor = '#ffffff';
    ctx.shadowBlur = 15;
    ctx.beginPath();
    ctx.arc(ball.x, ball.y, BALL_SIZE, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Score
    ctx.font = '32px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillStyle = 'rgba(255,255,255,0.1)';
    ctx.fillText(playerScore, W/4, 50);
    ctx.fillText(aiScore, 3*W/4, 50);

    if (!gameRunning) {
        ctx.fillStyle = '#ffaa33';
        ctx.font = '20px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('����� "����� ����"', W/2, H/2);
    }
}

function endGame() {
    gameOver = true;
    gameRunning = false;
    const finalScore = playerScore * 100;
    scoreDisplay.textContent = playerScore;
    if (!gameMessage.textContent) gameMessage.textContent = '���� ��������!';
    if (scoreSubmitted) return;
    scoreSubmitted = true;
    const formData = new FormData();
    formData.append('score', finalScore);
    fetch('pong.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

canvas.addEventListener('mousemove', (e) => {
    const rect = canvas.getBoundingClientRect();
    const scaleY = H / rect.height;
    const rawY = (e.clientY - rect.top) * scaleY;
    playerY = Math.max(0, Math.min(H - PADDLE_H, rawY - PADDLE_H/2));
});

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

resetGame();
gameLoop();
</script></body></html>
