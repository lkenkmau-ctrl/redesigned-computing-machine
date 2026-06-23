<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'invaders', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.invaders", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.invaders", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Инвейдеры — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
canvas { border: 2px solid rgba(255,136,0,0.25); background: #0a0500; border-radius: 8px; }
.controls-hint { display: flex; gap: 6px; justify-content: center; margin: 8px 0; flex-wrap: wrap; }
.key { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; padding: 4px 12px; font-size: 13px; color: #888; font-family: monospace; }
.game-message { font-size: 18px; font-weight: 600; min-height: 28px; margin: 8px 0; color: #ffaa33; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
                    <a href="snake.php">рџђЌ Р—РјРµР№РєР°</a>
                    <a href="tetris.php">рџ§Љ РўРµС‚СЂРёСЃ</a>
                    <a href="2048.php">рџ”ў 2048</a>
                    <a href="tictactoe.php">в­• РљСЂРµСЃС‚РёРєРё-РЅРѕР»РёРєРё</a>
                    <a href="guess.php">вќ“ РЈРіР°РґР°Р№ С‡РёСЃР»Рѕ</a>
                    <a href="memory.php">рџѓЏ РџР°РјСЏС‚СЊ</a>
                    <a href="clicker.php">рџ‘† РљР»РёРєРµСЂ</a>
                    <a href="quiz.php">рџ“ќ Р’РёРєС‚РѕСЂРёРЅР°</a>
                    <a href="flappy.php">рџђ¦ Flappy Bird</a>
                    <a href="reaction.php">вљЎ Reaction Test</a>
                    <a href="minesweeper.php">рџ’Ј РЎР°РїС‘СЂ</a>
                    <a href="hangman.php">рџ‘» Р’РёСЃРµР»РёС†Р°</a>
                    <a href="simon.php">рџ”ґ РЎР°Р№РјРѕРЅ</a>
                    <a href="pong.php">рџЏ“ РџРѕРЅРі</a>
                    <a href="invaders.php">рџ‘ѕ РРЅРІРµР№РґРµСЂС‹</a>
                    <a href="breakout.php">рџ§± РђСЂРєР°РЅРѕРёРґ</a>
                    <a href="sudoku.php">рџ§© РЎСѓРґРѕРєСѓ</a>
                    <a href="wordle.php">рџ”¤ Р’РѕСЂРґР»Рё</a>
                    <a href="dino.php">рџ¦– Р”РёРЅРѕР·Р°РІСЂРёРє</a>
                    <a href="rps.php">вњЉ РљР°РјРµРЅСЊ-РќРѕР¶РЅРёС†С‹</a>
                    <a href="typing.php">вЊЁпёЏ РџРµС‡Р°С‚СЊ</a>
                    <a href="color_match.php">рџЋЁ Р¦РІРµС‚</a>
                    <a href="balloon.php">рџЋ€ РЁР°СЂРёРєРё</a>
                    <a href="whack.php">рџ”Ё РљСЂРѕС‚</a>
                    <a href="hanoi.php">рџ—ј РҐР°РЅРѕР№</a>
                    <a href="connect4.php">рџ”ґ 4 РІ СЂСЏРґ</a>
                    <a href="math.php">рџ§® РњР°С‚РµРјР°С‚РёРєР°</a>
                    <a href="fifteen.php">рџ§© РџСЏС‚РЅР°С€РєРё</a>
                    <a href="asteroids.php">в„пёЏ РђСЃС‚РµСЂРѕРёРґС‹</a>
                    <a href="pacman.php">рџ‘ѕ РџР°РєРјР°РЅ</a></div></div><a href="donate.php" class="btn btn-sm">💰 Магазин</a><a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>👾 Инвейдеры</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Убито</span><span class="val" id="killsDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area"><canvas id="gameCanvas" width="500" height="400"></canvas></div>
<div class="controls-hint">
<span class="key">A / ←</span><span class="key">D / →</span><span class="key">Пробел — стрелять</span>
</div>
<div id="gameMessage" class="game-message"></div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const killsDisplay = document.getElementById('killsDisplay');
const gameMessage = document.getElementById('gameMessage');
const W = 500, H = 400;
const PLAYER_W = 44, PLAYER_H = 28;
const ALIEN_ROWS = 5, ALIEN_COLS = 6;
const ALIEN_W = 36, ALIEN_H = 28;

let player, aliens, bullets, alienBullets;
let score = 0;
let kills = 0;
let alienDir = 1;
let alienSpeed = 0.6;
let moveCounter = 0;
let gameOver = false;
let gameRunning = false;
let scoreSubmitted = false;
let keys = { left: false, right: false, space: false };
let shootCooldown = 0;
let frameCount = 0;

function createAliens() {
    aliens = [];
    for (let r = 0; r < ALIEN_ROWS; r++) {
        for (let c = 0; c < ALIEN_COLS; c++) {
            aliens.push({ x: 60 + c * 70, y: 40 + r * 44, w: ALIEN_W, h: ALIEN_H, alive: true });
        }
    }
}

function resetGame() {
    player = { x: W/2 - PLAYER_W/2, y: H - 40, w: PLAYER_W, h: PLAYER_H };
    bullets = [];
    alienBullets = [];
    score = 0;
    kills = 0;
    alienDir = 1;
    alienSpeed = 0.6;
    moveCounter = 0;
    gameOver = false;
    gameRunning = true;
    scoreSubmitted = false;
    shootCooldown = 0;
    frameCount = 0;
    scoreDisplay.textContent = '0';
    killsDisplay.textContent = '0';
    gameMessage.textContent = '';
    createAliens();
}

function shoot() {
    if (shootCooldown > 0) return;
    bullets.push({ x: player.x + player.w/2 - 2, y: player.y - 8, w: 4, h: 12 });
    shootCooldown = 12;
}

function alienShoot() {
    const alive = aliens.filter(a => a.alive);
    if (alive.length === 0) return;
    const shooter = alive[Math.floor(Math.random() * alive.length)];
    alienBullets.push({ x: shooter.x + shooter.w/2 - 3, y: shooter.y + shooter.h, w: 6, h: 12 });
}

function update() {
    if (!gameRunning || gameOver) return;

    frameCount++;
    if (shootCooldown > 0) shootCooldown--;

    // Player movement
    if (keys.left) player.x = Math.max(0, player.x - 4);
    if (keys.right) player.x = Math.min(W - player.w, player.x + 4);
    if (keys.space) shoot();

    // Alien movement
    moveCounter++;
    if (moveCounter >= 30) {
        moveCounter = 0;
        let hitEdge = false;
        for (const a of aliens) {
            if (!a.alive) continue;
            const nx = a.x + alienDir * 12;
            if (nx <= 5 || nx + a.w >= W - 5) { hitEdge = true; break; }
        }
        if (hitEdge) {
            alienDir = -alienDir;
            for (const a of aliens) {
                if (a.alive) a.y += 12;
            }
        } else {
            for (const a of aliens) {
                if (a.alive) a.x += alienDir * 12;
            }
        }
    }

    // Check aliens reached bottom
    for (const a of aliens) {
        if (a.alive && a.y + a.h >= player.y) {
            endGame();
            return;
        }
    }

    // Player bullets
    for (let i = bullets.length - 1; i >= 0; i--) {
        bullets[i].y -= 6;
        if (bullets[i].y + bullets[i].h < 0) { bullets.splice(i, 1); continue; }
        let hit = false;
        for (const a of aliens) {
            if (!a.alive) continue;
            if (bullets[i].x < a.x + a.w && bullets[i].x + bullets[i].w > a.x &&
                bullets[i].y < a.y + a.h && bullets[i].y + bullets[i].h > a.y) {
                a.alive = false;
                hit = true;
                kills++;
                killsDisplay.textContent = kills;
                break;
            }
        }
        if (hit) {
            bullets.splice(i, 1);
            score = kills * 10;
            scoreDisplay.textContent = score;
            if (aliens.every(a => !a.alive)) {
                gameMessage.textContent = '🎉 Победа! Все инвейдеры уничтожены!';
                endGame();
                return;
            }
        }
    }

    // Alien shooting
    if (frameCount % 40 === 0 && aliens.some(a => a.alive)) alienShoot();

    // Alien bullets
    for (let i = alienBullets.length - 1; i >= 0; i--) {
        alienBullets[i].y += 3;
        if (alienBullets[i].y > H) { alienBullets.splice(i, 1); continue; }
        if (alienBullets[i].x < player.x + player.w && alienBullets[i].x + alienBullets[i].w > player.x &&
            alienBullets[i].y < player.y + player.h && alienBullets[i].y + alienBullets[i].h > player.y) {
            alienBullets.splice(i, 1);
            endGame();
            return;
        }
    }
}

function draw() {
    ctx.fillStyle = '#0a0500';
    ctx.fillRect(0, 0, W, H);

    // Stars
    ctx.fillStyle = 'rgba(255,255,255,0.08)';
    for (let i = 0; i < 50; i++) {
        const sx = (i * 137 + 50) % W, sy = (i * 97 + 30) % H;
        ctx.fillRect(sx, sy, 2, 2);
    }

    // Aliens
    for (const a of aliens) {
        if (!a.alive) continue;
        const row = Math.floor((a.y - 40) / 44);
        if (row % 2 === 0) {
            ctx.fillStyle = '#88ff44';
            ctx.shadowColor = '#88ff44';
        } else {
            ctx.fillStyle = '#ff44aa';
            ctx.shadowColor = '#ff44aa';
        }
        ctx.shadowBlur = 8;
        // Alien body
        ctx.fillRect(a.x + 4, a.y + 4, a.w - 8, a.h - 8);
        ctx.shadowBlur = 0;
        // Eyes
        ctx.fillStyle = '#fff';
        ctx.fillRect(a.x + 8, a.y + 8, 6, 6);
        ctx.fillRect(a.x + a.w - 14, a.y + 8, 6, 6);
    }

    // Player
    ctx.fillStyle = '#4488ff';
    ctx.shadowColor = '#4488ff';
    ctx.shadowBlur = 12;
    ctx.beginPath();
    ctx.moveTo(player.x + player.w/2, player.y);
    ctx.lineTo(player.x + player.w, player.y + player.h);
    ctx.lineTo(player.x, player.y + player.h);
    ctx.closePath();
    ctx.fill();
    ctx.shadowBlur = 0;

    // Player bullets
    ctx.fillStyle = '#ffcc00';
    ctx.shadowColor = '#ffcc00';
    ctx.shadowBlur = 8;
    for (const b of bullets) ctx.fillRect(b.x, b.y, b.w, b.h);
    ctx.shadowBlur = 0;

    // Alien bullets
    ctx.fillStyle = '#ff4444';
    ctx.shadowColor = '#ff4444';
    ctx.shadowBlur = 8;
    for (const b of alienBullets) ctx.fillRect(b.x, b.y, b.w, b.h);
    ctx.shadowBlur = 0;

    if (!gameRunning && !gameOver) {
        ctx.fillStyle = '#ffaa33';
        ctx.font = '20px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('Нажми "Новая игра"', W/2, H/2);
    }
}

function endGame() {
    gameOver = true;
    gameRunning = false;
    const finalScore = kills * 10;
    if (!gameMessage.textContent) gameMessage.textContent = '💀 Игра окончена! Уничтожено: ' + kills;
    if (scoreSubmitted) return;
    scoreSubmitted = true;
    const formData = new FormData();
    formData.append('score', finalScore);
    fetch('invaders.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'a' || e.key === 'A' || e.key === 'ArrowLeft') { keys.left = true; e.preventDefault(); }
    if (e.key === 'd' || e.key === 'D' || e.key === 'ArrowRight') { keys.right = true; e.preventDefault(); }
    if (e.key === ' ') { keys.space = true; e.preventDefault(); }
});

document.addEventListener('keyup', (e) => {
    if (e.key === 'a' || e.key === 'A' || e.key === 'ArrowLeft') { keys.left = false; e.preventDefault(); }
    if (e.key === 'd' || e.key === 'D' || e.key === 'ArrowRight') { keys.right = false; e.preventDefault(); }
    if (e.key === ' ') { keys.space = false; e.preventDefault(); }
});

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

resetGame();
gameLoop();
</script></body></html>
