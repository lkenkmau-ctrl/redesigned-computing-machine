<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'asteroids', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.asteroids", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.asteroids", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>РђСЃС‚РµСЂРѕРёРґС‹ вЂ” DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#gameCanvas { border: 2px solid rgba(255,136,0,0.25); background: #050a10; border-radius: 8px; }
.controls-hint { display: flex; gap: 4px; justify-content: center; margin: 12px 0; flex-wrap: wrap; }
.key { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; padding: 6px 12px; font-size: 12px; color: #888; font-family: monospace; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">рџЋ® РРіСЂС‹ в–ѕ</button><div class="dropdown-content">
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
</div></div><a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a><a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>в„пёЏ РђСЃС‚РµСЂРѕРёРґС‹</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">РЎС‡С‘С‚</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Р РµРєРѕСЂРґ</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div><div class="game-info-item"><span class="lbl">Р’РѕР»РЅР°</span><span class="val" id="waveDisplay">1</span></div></div>
<div class="game-area"><canvas id="gameCanvas" width="500" height="500"></canvas></div>
<div class="controls-hint">
<span class="key">W / в†‘</span><span class="key">A / в†ђ</span><span class="key">S / в†“</span><span class="key">D / в†’</span><span class="key">РџСЂРѕР±РµР» вЂ” РѕРіРѕРЅСЊ</span>
</div>
<div class="game-controls"><button class="btn" onclick="resetGame()">рџ”„ РќРѕРІР°СЏ РёРіСЂР°</button></div>
</div></div>
<footer><p>DonateCraft вЂ” Р·Р°СЂР°Р±Р°С‚С‹РІР°Р№ РґРѕРЅР°С‚РЅС‹Рµ РїРѕРёРЅС‚С‹ Р·Р° РјРёРЅРё-РёРіСЂС‹</p></footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const waveDisplay = document.getElementById('waveDisplay');
const W = 500, H = 500;

let ship, bullets, asteroids, score, wave, gameOver, saved, keys;
let particleEmitters = [];

function resetGame() {
    ship = { x: W/2, y: H/2, angle: -Math.PI/2, vx: 0, vy: 0, radius: 14 };
    bullets = [];
    asteroids = [];
    score = 0;
    wave = 0;
    gameOver = false;
    saved = false;
    keys = {};
    particleEmitters = [];
    scoreDisplay.textContent = '0';
    waveDisplay.textContent = '1';
    spawnWave();
}

function spawnWave() {
    wave++;
    waveDisplay.textContent = wave;
    const count = 3 + wave;
    for (let i = 0; i < count; i++) {
        let x, y;
        const side = Math.floor(Math.random() * 4);
        if (side === 0) { x = Math.random() * W; y = -30; }
        else if (side === 1) { x = W + 30; y = Math.random() * H; }
        else if (side === 2) { x = Math.random() * W; y = H + 30; }
        else { x = -30; y = Math.random() * H; }
        const angle = Math.random() * Math.PI * 2;
        const speed = 0.5 + Math.random() * 1.5;
        asteroids.push({
            x, y, vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed,
            radius: 24 + Math.random() * 16, size: 'big'
        });
    }
}

function shoot() {
    if (gameOver) return;
    const bx = ship.x + Math.cos(ship.angle) * (ship.radius + 8);
    const by = ship.y + Math.sin(ship.angle) * (ship.radius + 8);
    const speed = 6;
    bullets.push({
        x: bx, y: by, vx: Math.cos(ship.angle) * speed, vy: Math.sin(ship.angle) * speed,
        radius: 3, life: 60
    });
}

function splitAsteroid(a) {
    let newSize, points;
    if (a.size === 'big') { newSize = 'medium'; points = 20; a.radius = 16; }
    else if (a.size === 'medium') { newSize = 'small'; points = 50; a.radius = 10; }
    else { points = 100; return points; }
    a.size = newSize;
    for (let i = 0; i < 2; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = 1 + Math.random() * 2;
        asteroids.push({
            x: a.x + (Math.random() - 0.5) * 10, y: a.y + (Math.random() - 0.5) * 10,
            vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed,
            radius: a.radius, size: newSize
        });
    }
    return points;
}

function update() {
    if (gameOver) return;

    // Ship rotation/thrust
    if (keys['ArrowLeft'] || keys['KeyA']) ship.angle -= 0.05;
    if (keys['ArrowRight'] || keys['KeyD']) ship.angle += 0.05;
    if (keys['ArrowUp'] || keys['KeyW']) {
        ship.vx += Math.cos(ship.angle) * 0.12;
        ship.vy += Math.sin(ship.angle) * 0.12;
    }
    if (keys['ArrowDown'] || keys['KeyS']) {
        ship.vx -= Math.cos(ship.angle) * 0.08;
        ship.vy -= Math.sin(ship.angle) * 0.08;
    }

    // Ship wrap
    ship.x += ship.vx; ship.y += ship.vy;
    ship.vx *= 0.99; ship.vy *= 0.99;
    if (ship.x < 0) ship.x += W; if (ship.x > W) ship.x -= W;
    if (ship.y < 0) ship.y += H; if (ship.y > H) ship.y -= H;

    // Bullets
    for (let i = bullets.length - 1; i >= 0; i--) {
        const b = bullets[i];
        b.x += b.vx; b.y += b.vy;
        b.life--;
        if (b.life <= 0 || b.x < 0 || b.x > W || b.y < 0 || b.y > H) {
            bullets.splice(i, 1); continue;
        }
        for (let j = asteroids.length - 1; j >= 0; j--) {
            const a = asteroids[j];
            const dx = b.x - a.x, dy = b.y - a.y;
            if (dx * dx + dy * dy < (b.radius + a.radius) ** 2) {
                const pts = splitAsteroid(a);
                if (pts !== undefined) { score += pts; scoreDisplay.textContent = score; }
                bullets.splice(i, 1);
                asteroids.splice(j, 1);
                break;
            }
        }
    }

    // Asteroids wrap
    for (const a of asteroids) {
        a.x += a.vx; a.y += a.vy;
        if (a.x < -50) a.x += W + 100; if (a.x > W + 50) a.x -= W + 100;
        if (a.y < -50) a.y += H + 100; if (a.y > H + 50) a.y -= H + 100;
    }

    // Ship hit
    for (const a of asteroids) {
        const dx = ship.x - a.x, dy = ship.y - a.y;
        if (dx * dx + dy * dy < (ship.radius + a.radius) ** 2) {
            gameOver = true;
            submitScore();
            return;
        }
    }

    // Next wave
    if (asteroids.length === 0) spawnWave();
}

function submitScore() {
    if (saved) return;
    saved = true;
    const formData = new FormData();
    formData.append('score', score);
    fetch('asteroids.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function draw() {
    ctx.fillStyle = '#050a10'; ctx.fillRect(0, 0, W, H);

    // Asteroids
    for (const a of asteroids) {
        ctx.strokeStyle = 'rgba(200,180,150,0.8)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        const pts = 8 + Math.floor(a.radius / 4);
        for (let i = 0; i < pts; i++) {
            const angle = (i / pts) * Math.PI * 2;
            const r = a.radius * (0.7 + Math.random() * 0.3);
            const px = a.x + Math.cos(angle) * r;
            const py = a.y + Math.sin(angle) * r;
            i === 0 ? ctx.moveTo(px, py) : ctx.lineTo(px, py);
        }
        ctx.closePath(); ctx.stroke();
    }

    // Bullets
    ctx.fillStyle = '#ffcc33';
    for (const b of bullets) {
        ctx.beginPath(); ctx.arc(b.x, b.y, b.radius, 0, Math.PI * 2); ctx.fill();
    }

    // Ship
    ctx.save();
    ctx.translate(ship.x, ship.y);
    ctx.rotate(ship.angle);
    ctx.strokeStyle = '#66ccff';
    ctx.lineWidth = 2;
    ctx.shadowColor = '#66ccff'; ctx.shadowBlur = 10;
    ctx.beginPath();
    ctx.moveTo(ship.radius + 4, 0);
    ctx.lineTo(-ship.radius + 2, -ship.radius * 0.7);
    ctx.lineTo(-ship.radius + 2, ship.radius * 0.7);
    ctx.closePath(); ctx.stroke();
    ctx.shadowBlur = 0;
    ctx.restore();

    if (gameOver) {
        ctx.fillStyle = 'rgba(0,0,0,0.6)';
        ctx.fillRect(0, 0, W, H);
        ctx.fillStyle = '#ff4444';
        ctx.font = 'bold 36px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('рџ’Ґ РРіСЂР° РѕРєРѕРЅС‡РµРЅР°!', W/2, H/2 - 20);
        ctx.fillStyle = '#ffaa33';
        ctx.font = '20px Inter, sans-serif';
        ctx.fillText('РЎС‡С‘С‚: ' + score, W/2, H/2 + 30);
    }
}

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

document.addEventListener('keydown', e => {
    keys[e.code] = true;
    if (e.code === 'Space') { e.preventDefault(); shoot(); }
});
document.addEventListener('keyup', e => { keys[e.code] = false; });

resetGame();
gameLoop();
</script></body></html>
