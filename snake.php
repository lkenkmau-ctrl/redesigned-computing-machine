<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Р—РјРµР№РєР°</title>
<link rel="stylesheet" href="style.css">
<style>
#gameCanvas { border: 2px solid rgba(0,170,0,0.3); background: #0a0a15; border-radius: 8px; }
.controls-hint { display: flex; gap: 4px; justify-content: center; margin: 12px 0; }
.key { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; padding: 6px 14px; font-size: 13px; color: #888; font-family: monospace; }
</style>
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">рџЋ® РРіСЂС‹ в–ѕ</button>
                <div class="dropdown-content">
                    <a href="snake.php">?? Змейка</a>
                    <a href="tetris.php">?? Тетрис</a>
                    <a href="2048.php">?? 2048</a>
                    <a href="tictactoe.php">? Крестики-нолики</a>
                    <a href="guess.php">? Угадай число</a>
                    <a href="memory.php">?? Память</a>
                    <a href="clicker.php">?? Кликер</a>
                    <a href="quiz.php">?? Викторина</a>
                    <a href="flappy.php">?? Flappy Bird</a>
                    <a href="reaction.php">? Reaction Test</a>
                    <a href="minesweeper.php">?? Сапёр</a>
                    <a href="hangman.php">?? Виселица</a>
                    <a href="simon.php">?? Саймон</a>
                    <a href="pong.php">?? Понг</a>
                    <a href="invaders.php">?? Инвейдеры</a>
                    <a href="breakout.php">?? Арканоид</a>
                    <a href="sudoku.php">?? Судоку</a>
                    <a href="wordle.php">?? Вордли</a>
                    <a href="dino.php">?? Динозаврик</a>
                    <a href="rps.php">? Камень-Ножницы</a>
                    <a href="typing.php">?? Печать</a>
                    <a href="color_match.php">?? Цвет</a>
                    <a href="balloon.php">?? Шарики</a>
                    <a href="whack.php">?? Крот</a>
                    <a href="hanoi.php">?? Ханой</a>
                    <a href="connect4.php">?? 4 в ряд</a>
                    <a href="math.php">?? Математика</a>
                    <a href="fifteen.php">?? Пятнашки</a>
                    <a href="asteroids.php">?? Астероиды</a>
                    <a href="pacman.php">?? Пакман</a>
                </div>
            </div>
            <a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a>
            <a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>рџђЌ Р—РјРµР№РєР°</h1>
        <p style="color:#888;margin-bottom:16px;">РЎРѕР±РёСЂР°Р№ РµРґСѓ, РїСЂРѕС…РѕРґРё СѓСЂРѕРІРЅРё Рё Р·Р°СЂР°Р±Р°С‚С‹РІР°Р№ РѕС‡РєРё!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">РЈСЂРѕРІРµРЅСЊ</span><span class="val" id="scoreDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">РЎСЉРµРґРµРЅРѕ</span><span class="val" id="foodEatenDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Р—Р°СЂР°Р±РѕС‚Р°РЅРѕ</span><span class="val" id="pointsDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">РЎРєРѕСЂРѕСЃС‚СЊ</span><span class="val" id="speedDisplay">0</span></div>
        </div>

        <canvas id="gameCanvas" width="400" height="400"></canvas>

        <div class="controls-hint">
            <span class="key">W</span>
            <span class="key">A</span>
            <span class="key">S</span>
            <span class="key">D</span>
            <span class="key">в¬†</span>
            <span class="key">в¬‡</span>
            <span class="key">в¬…</span>
            <span class="key">вћЎ</span>
        </div>

        <div class="game-controls">
            <button id="startBtn" class="btn" style="min-width:140px;">в–¶ РЎС‚Р°СЂС‚</button>
            <a href="profile.php" class="btn btn-outline">РџСЂРѕС„РёР»СЊ</a>
        </div>

        <div id="result" style="font-size:18px;font-weight:600;min-height:30px;"></div>

        <div style="margin-top:16px;background:rgba(22,33,62,0.5);border-radius:10px;padding:16px;text-align:left;font-size:13px;color:#888;">
            <strong style="color:#aaa;">РџСЂР°РІРёР»Р°:</strong> РљР°Р¶РґС‹Рµ 5 СЃСЉРµРґРµРЅРЅС‹С… РєСѓСЃРѕС‡РєРѕРІ = РЅРѕРІС‹Р№ СѓСЂРѕРІРµРЅСЊ. Р—Р° РєР°Р¶РґС‹Р№ СѓСЂРѕРІРµРЅСЊ РЅР°С‡РёСЃР»СЏРµС‚СЃСЏ <strong style="color:#00ff00;">+100 РѕС‡РєРѕРІ</strong>. РЎ РєР°Р¶РґС‹Рј СѓСЂРѕРІРЅРµРј СЃРєРѕСЂРѕСЃС‚СЊ СЂР°СЃС‚С‘С‚!
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const foodEatenDisplay = document.getElementById('foodEatenDisplay');
const pointsDisplay = document.getElementById('pointsDisplay');
const speedDisplay = document.getElementById('speedDisplay');
const startBtn = document.getElementById('startBtn');
const resultDiv = document.getElementById('result');

const gridSize = 20;
const tileCount = canvas.width / gridSize;

let snake, direction, food, level, foodEaten, gameRunning, gameLoop;
let baseSpeed = 200;
let currentPoints = 0;
let saved = false;

function init() {
    snake = [{x: 10, y: 10}];
    direction = {x: 1, y: 0};
    food = spawnFood();
    level = 1;
    foodEaten = 0;
    gameRunning = false;
    saved = false;
    currentPoints = 0;
    updateDisplay();
    draw();
}

function spawnFood() {
    let pos;
    do {
        pos = {
            x: Math.floor(Math.random() * tileCount),
            y: Math.floor(Math.random() * tileCount)
        };
    } while (snake.some(s => s.x === pos.x && s.y === pos.y));
    return pos;
}

function updateDisplay() {
    scoreDisplay.textContent = level;
    foodEatenDisplay.textContent = foodEaten;
    pointsDisplay.textContent = '+' + currentPoints;
    speedDisplay.textContent = Math.max(80, baseSpeed - (level - 1) * 15) + 'ms';
}

function draw() {
    ctx.fillStyle = '#0a0a15';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    for (let i = 0; i < tileCount; i++) {
        for (let j = 0; j < tileCount; j++) {
            if ((i + j) % 2 === 0) {
                ctx.fillStyle = 'rgba(255,255,255,0.02)';
                ctx.fillRect(i * gridSize, j * gridSize, gridSize, gridSize);
            }
        }
    }

    snake.forEach((seg, i) => {
        if (i === 0) {
            ctx.fillStyle = '#00ff00';
            ctx.shadowColor = '#00ff00';
            ctx.shadowBlur = 8;
        } else {
            ctx.fillStyle = '#00aa00';
            ctx.shadowBlur = 0;
        }
        ctx.fillRect(seg.x * gridSize + 1, seg.y * gridSize + 1, gridSize - 2, gridSize - 2);
        ctx.shadowBlur = 0;
    });

    ctx.fillStyle = '#ff4444';
    ctx.shadowColor = '#ff4444';
    ctx.shadowBlur = 10;
    ctx.beginPath();
    ctx.arc(food.x * gridSize + gridSize/2, food.y * gridSize + gridSize/2, gridSize/2 - 2, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;
}

function update() {
    if (!gameRunning) return;
    const head = {x: snake[0].x + direction.x, y: snake[0].y + direction.y};

    if (head.x < 0 || head.x >= tileCount || head.y < 0 || head.y >= tileCount) { gameOver(); return; }
    if (snake.some(s => s.x === head.x && s.y === head.y)) { gameOver(); return; }

    snake.unshift(head);

    if (head.x === food.x && head.y === food.y) {
        foodEaten++;
        currentPoints += 100;
        food = spawnFood();
        if (foodEaten % 5 === 0) {
            level++;
            let newSpeed = Math.max(80, baseSpeed - (level - 1) * 15);
            if (gameRunning) { clearInterval(gameLoop); gameLoop = setInterval(update, newSpeed); }
        }
        updateDisplay();
    } else {
        snake.pop();
    }
    draw();
}

function gameOver() {
    gameRunning = false;
    clearInterval(gameLoop);
    if (!saved) {
        saved = true;
        fetch('api.php?action=save_score&game=snake&level=' + level + '&points=' + currentPoints)
            .then(r => r.text())
            .then(t => { resultDiv.innerHTML = 'рџЋ® РРіСЂР° РѕРєРѕРЅС‡РµРЅР°! РЈСЂРѕРІРµРЅСЊ: <strong style="color:#00ff00;">' + level + '</strong> | +<strong style="color:#ffd700;">' + currentPoints + '</strong> РѕС‡РєРѕРІ Р·Р°С‡РёСЃР»РµРЅРѕ'; })
            .catch(() => { resultDiv.innerHTML = 'вљ пёЏ РРіСЂР° РѕРєРѕРЅС‡РµРЅР°! РћС€РёР±РєР° СЃРѕС…СЂР°РЅРµРЅРёСЏ.'; });
    }
    startBtn.textContent = 'рџ”„ Р—Р°РЅРѕРІРѕ';
}

document.addEventListener('keydown', e => {
    if (!gameRunning) return;
    switch(e.key) {
        case 'ArrowUp': case 'w': case 'W': if (direction.y === 0) direction = {x: 0, y: -1}; break;
        case 'ArrowDown': case 's': case 'S': if (direction.y === 0) direction = {x: 0, y: 1}; break;
        case 'ArrowLeft': case 'a': case 'A': if (direction.x === 0) direction = {x: -1, y: 0}; break;
        case 'ArrowRight': case 'd': case 'D': if (direction.x === 0) direction = {x: 1, y: 0}; break;
    }
});

startBtn.addEventListener('click', () => {
    init();
    gameRunning = true;
    startBtn.textContent = 'в–¶ РРіСЂР°...';
    resultDiv.innerHTML = '';
    let speed = Math.max(80, baseSpeed - (level - 1) * 15);
    if (gameLoop) clearInterval(gameLoop);
    gameLoop = setInterval(update, speed);
    updateDisplay();
    draw();
});

init();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
