<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'balloon', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.balloon", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.balloon", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Шарики</title>
<link rel="stylesheet" href="style.css">
<style>
#balloonCanvas{display:block;margin:0 auto;border:2px solid rgba(255,136,0,0.2);border-radius:8px;background:linear-gradient(to bottom,#0d1b2a,#1b2e3e);cursor:crosshair}
.legend{display:flex;gap:16px;justify-content:center;margin:10px 0;font-size:14px;flex-wrap:wrap}
.legend-item{display:flex;align-items:center;gap:6px}
.legend-dot{width:14px;height:14px;border-radius:50%;display:inline-block}
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
        <h1>🎈 Шарики</h1>
        <p style="color:#888;margin-bottom:16px;">Лопай шарики, набирай очки и ставь рекорды!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
            <div class="game-info-item"><span class="lbl">Время</span><span class="val" id="timerDisplay">30</span></div>
            <div class="game-info-item"><span class="lbl">Пропущено</span><span class="val" id="missedDisplay">0</span></div>
        </div>

        <div class="legend">
            <span class="legend-item"><span class="legend-dot" style="background:#ff3333"></span> Красные 10</span>
            <span class="legend-item"><span class="legend-dot" style="background:#3388ff"></span> Синие 20</span>
            <span class="legend-item"><span class="legend-dot" style="background:#ffd700"></span> Золотые 50</span>
        </div>

        <canvas id="balloonCanvas" width="500" height="550"></canvas>

        <div class="game-controls">
            <button class="btn" onclick="resetGame()" style="min-width:140px;">🔄 Новая игра</button>
            <a href="profile.php" class="btn btn-outline">Выйти</a>
        </div>

        <div id="result" style="font-size:18px;font-weight:600;min-height:30px;"></div>

        <div style="margin-top:16px;background:rgba(22,33,62,0.5);border-radius:10px;padding:16px;text-align:left;font-size:13px;color:#888;">
            <strong style="color:#aaa;">Правила:</strong> Лопай шарики кликом мыши. Красные = 10, Синие = 20, Золотые = 50 очков. Не пропусти больше 10 шариков! Удачи!
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('balloonCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const timerDisplay = document.getElementById('timerDisplay');
const missedDisplay = document.getElementById('missedDisplay');

const W = 500, H = 550;
const GAME_TIME = 30;
const MAX_MISSED = 10;
const BALLOON_RADIUS = 22;
let balloons = [];
let score = 0;
let missed = 0;
let timeLeft = GAME_TIME;
let gameActive = false;
let timer = null;
let spawnInterval = null;
let animId = null;

const balloonColors = [
  { fill: '#ff3333', stroke: '#cc0000', pts: 10, weight: 65 },
  { fill: '#3388ff', stroke: '#0055cc', pts: 20, weight: 25 },
  { fill: '#ffd700', stroke: '#cc9900', pts: 50, weight: 10 },
];

function weightedColor() {
  const r = Math.random() * 100;
  let cum = 0;
  for (const c of balloonColors) {
    cum += c.weight;
    if (r <= cum) return c;
  }
  return balloonColors[0];
}

function createBalloon() {
  const color = weightedColor();
  return {
    x: Math.random() * (W - 60) + 30,
    y: H + 30,
    speed: 0.6 + Math.random() * 0.8,
    color: color,
    popped: false,
  };
}

function resetGame() {
  clearInterval(timer);
  clearInterval(spawnInterval);
  cancelAnimationFrame(animId);
  balloons = [];
  score = 0;
  missed = 0;
  timeLeft = GAME_TIME;
  gameActive = true;
  scoreDisplay.textContent = '0';
  timerDisplay.textContent = GAME_TIME;
  missedDisplay.textContent = '0';
  document.getElementById('result').textContent = '';
  timer = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = timeLeft;
    if (timeLeft <= 0) endGame();
  }, 1000);
  spawnInterval = setInterval(() => {
    if (gameActive) balloons.push(createBalloon());
  }, 600);
  loop();
}

function endGame() {
  gameActive = false;
  clearInterval(timer);
  clearInterval(spawnInterval);
  const formData = new FormData();
  formData.append('score', score);
  fetch('balloon.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
    .catch(() => {});
}

function update() {
  for (let i = balloons.length - 1; i >= 0; i--) {
    const b = balloons[i];
    b.y -= b.speed;
    if (b.y < -30) {
      if (!b.popped) {
        missed++;
        missedDisplay.textContent = missed;
        if (missed >= MAX_MISSED) endGame();
      }
      balloons.splice(i, 1);
    }
  }
}

function draw() {
  ctx.clearRect(0, 0, W, H);
  ctx.fillStyle = '#0d1b2a';
  ctx.fillRect(0, 0, W, H);
  const grad = ctx.createLinearGradient(0, 0, 0, H);
  grad.addColorStop(0, '#0d1b2a');
  grad.addColorStop(0.5, '#152238');
  grad.addColorStop(1, '#1a2a3e');
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, W, H);
  for (const b of balloons) {
    if (b.popped) continue;
    const x = b.x, y = b.y;
    const grad2 = ctx.createRadialGradient(x - 6, y - 6, 2, x, y, BALLOON_RADIUS);
    grad2.addColorStop(0, lightenColor(b.color.fill, 40));
    grad2.addColorStop(1, b.color.fill);
    ctx.beginPath();
    ctx.arc(x, y, BALLOON_RADIUS, 0, Math.PI * 2);
    ctx.fillStyle = grad2;
    ctx.fill();
    ctx.strokeStyle = b.color.stroke;
    ctx.lineWidth = 2;
    ctx.stroke();
    ctx.fillStyle = 'rgba(255,255,255,0.25)';
    ctx.beginPath();
    ctx.arc(x - 6, y - 7, 6, 0, Math.PI * 2);
    ctx.fill();
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 14px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(b.color.pts, x, y + 1);
    ctx.beginPath();
    ctx.moveTo(x, y + BALLOON_RADIUS);
    ctx.quadraticCurveTo(x + 4, y + BALLOON_RADIUS + 8, x, y + BALLOON_RADIUS + 16);
    ctx.quadraticCurveTo(x - 4, y + BALLOON_RADIUS + 8, x, y + BALLOON_RADIUS);
    ctx.fillStyle = b.color.stroke;
    ctx.fill();
  }
  if (missed >= MAX_MISSED) {
    ctx.fillStyle = '#ff4444';
    ctx.font = '32px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('Ты пропустил слишком много!', W/2, H/2);
  } else if (!gameActive) {
    ctx.fillStyle = '#ffaa33';
    ctx.font = '28px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('Игра окончена!', W/2, H/2);
    ctx.font = '18px Inter, sans-serif';
    ctx.fillText('Счёт: ' + score, W/2, H/2 + 36);
  }
}

function lightenColor(hex, amt) {
  let r = parseInt(hex.slice(1,3), 16);
  let g = parseInt(hex.slice(3,5), 16);
  let b = parseInt(hex.slice(5,7), 16);
  r = Math.min(255, r + amt);
  g = Math.min(255, g + amt);
  b = Math.min(255, b + amt);
  return '#' + r.toString(16).padStart(2,'0') + g.toString(16).padStart(2,'0') + b.toString(16).padStart(2,'0');
}

function loop() {
  if (!gameActive && balloons.length === 0) { draw(); return; }
  update();
  draw();
  animId = requestAnimationFrame(loop);
}

canvas.addEventListener('click', e => {
  if (!gameActive) return;
  const rect = canvas.getBoundingClientRect();
  const sx = canvas.width / rect.width;
  const sy = canvas.height / rect.height;
  const mx = (e.clientX - rect.left) * sx;
  const my = (e.clientY - rect.top) * sy;
  let hit = false;
  for (let i = balloons.length - 1; i >= 0; i--) {
    const b = balloons[i];
    if (b.popped) continue;
    const dx = mx - b.x, dy = my - b.y;
    if (dx * dx + dy * dy <= BALLOON_RADIUS * BALLOON_RADIUS) {
      b.popped = true;
      score += b.color.pts;
      scoreDisplay.textContent = score;
      hit = true;
      balloons.splice(i, 1);
      break;
    }
  }
});
</script>
</body>
</html>
