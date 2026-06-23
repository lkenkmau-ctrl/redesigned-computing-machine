<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>РљР»РёРєРµСЂ</title>
<link rel="stylesheet" href="style.css">
<style>
.click-btn {
    width: 240px; height: 240px; border-radius: 50%; border: none;
    background: linear-gradient(135deg, #00aa00, #005500);
    color: #fff; font-size: 48px; font-weight: 800; cursor: pointer;
    transition: all 0.1s ease; box-shadow: 0 0 40px rgba(0,170,0,0.3);
    user-select: none; margin: 20px auto; display: block; position: relative;
}
.click-btn:hover { transform: scale(1.05); box-shadow: 0 0 60px rgba(0,170,0,0.5); }
.click-btn:active { transform: scale(0.95); }
.click-btn:disabled { opacity: 0.4; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
.click-btn .click-count { font-size: 20px; font-weight: 400; display: block; margin-top: 4px; opacity: 0.8; }
.timer-ring { width: 200px; height: 200px; margin: 0 auto 20px; position: relative; }
.timer-ring svg { transform: rotate(-90deg); }
.timer-ring .bg { fill: none; stroke: rgba(255,255,255,0.05); stroke-width: 6; }
.timer-ring .progress { fill: none; stroke: #00ff00; stroke-width: 6; stroke-linecap: round; transition: stroke-dashoffset 0.3s ease, stroke 0.3s ease; }
.timer-text { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 800; }
.timer-text.urgent { color: #ff4444; }
.click-fly {
    position: fixed; pointer-events: none; font-size: 24px; font-weight: 700;
    color: #00ff00; animation: flyUp 0.8s ease forwards; z-index: 999;
}
@keyframes flyUp { 0% { opacity: 1; transform: translateY(0) scale(1); } 100% { opacity: 0; transform: translateY(-80px) scale(0.5); } }
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
        <h1>рџ–±пёЏ РљР»РёРєРµСЂ</h1>
        <p style="color:#888;margin-bottom:16px;">РљР»РёРєР°Р№ РєР°Рє РјРѕР¶РЅРѕ Р±С‹СЃС‚СЂРµРµ Р·Р° 10 СЃРµРєСѓРЅРґ!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">РљР»РёРєРѕРІ</span><span class="val" id="clicksDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">РЎС‡С‘С‚</span><span class="val" id="scoreDisplay">0</span></div>
        </div>

        <div class="timer-ring">
            <svg width="200" height="200" viewBox="0 0 200 200">
                <circle class="bg" cx="100" cy="100" r="88"/>
                <circle class="progress" id="timerProgress" cx="100" cy="100" r="88" stroke-dasharray="553" stroke-dashoffset="0"/>
            </svg>
            <div class="timer-text" id="timerDisplay">10</div>
        </div>

        <button class="click-btn" id="clickBtn" disabled>
            рџ‘†
            <span class="click-count" id="clickLabel">РќР°Р¶РјРё РЎС‚Р°СЂС‚</span>
        </button>

        <div class="game-controls">
            <button id="startBtn" class="btn" style="min-width:140px;">рџљЂ РЎС‚Р°СЂС‚</button>
        </div>

        <div id="result" style="font-size:18px;font-weight:600;min-height:30px;"></div>
    </div>
</div>

<script>
const clickBtn = document.getElementById('clickBtn');
const startBtn = document.getElementById('startBtn');
const clicksDisplay = document.getElementById('clicksDisplay');
const scoreDisplay = document.getElementById('scoreDisplay');
const timerDisplay = document.getElementById('timerDisplay');
const timerProgress = document.getElementById('timerProgress');
const clickLabel = document.getElementById('clickLabel');
const resultDiv = document.getElementById('result');

const DURATION = 10;
let clicks, timeLeft, gameRunning, timerInterval, saved;
const circumference = 553;

function newGame() {
    clicks = 0;
    timeLeft = DURATION;
    gameRunning = false;
    saved = false;
    clickBtn.disabled = true;
    startBtn.disabled = false;
    startBtn.textContent = 'рџљЂ РЎС‚Р°СЂС‚';
    resultDiv.innerHTML = '';
    updateDisplay();
}

function updateDisplay() {
    clicksDisplay.textContent = clicks;
    scoreDisplay.textContent = clicks * 10;
    timerDisplay.textContent = timeLeft;
    timerDisplay.className = timeLeft <= 3 ? 'timer-text urgent' : 'timer-text';
    let offset = circumference - (timeLeft / DURATION) * circumference;
    timerProgress.setAttribute('stroke-dashoffset', offset);
    if (timeLeft <= 3) {
        timerProgress.setAttribute('stroke', '#ff4444');
    } else {
        timerProgress.setAttribute('stroke', '#00ff00');
    }
    clickLabel.textContent = clicks + ' РєР»РёРєРѕРІ';
}

function startGame() {
    clicks = 0;
    timeLeft = DURATION;
    gameRunning = true;
    saved = false;
    clickBtn.disabled = false;
    startBtn.disabled = true;
    startBtn.textContent = 'вЏі РРіСЂР°...';
    resultDiv.innerHTML = '';
    updateDisplay();

    timerInterval = setInterval(() => {
        timeLeft--;
        updateDisplay();
        if (timeLeft <= 0) {
            endGame();
        }
    }, 1000);
}

function endGame() {
    gameRunning = false;
    clearInterval(timerInterval);
    clickBtn.disabled = true;
    startBtn.disabled = false;
    startBtn.textContent = 'рџ”„ Р•С‰С‘ СЂР°Р·';

    let score = clicks * 10;

    if (!saved) {
        saved = true;
        fetch('api.php?action=save_score&game=clicker&level=1&points=' + score)
            .then(r => r.text())
            .then(t => {
                resultDiv.innerHTML = 'вЏ° Р’СЂРµРјСЏ РІС‹С€Р»Рѕ! +<strong style="color:#ffd700;">' + score + '</strong> РѕС‡РєРѕРІ Р·Р°С‡РёСЃР»РµРЅРѕ';
            })
            .catch(() => {
                resultDiv.innerHTML = 'вЏ° Р’СЂРµРјСЏ РІС‹С€Р»Рѕ! вљ пёЏ РћС€РёР±РєР° СЃРѕС…СЂР°РЅРµРЅРёСЏ.';
            });
    }
}

function spawnFly(el) {
    const rect = el.getBoundingClientRect();
    const fly = document.createElement('div');
    fly.className = 'click-fly';
    fly.textContent = '+1';
    fly.style.left = (rect.left + Math.random() * rect.width - 20) + 'px';
    fly.style.top = (rect.top + Math.random() * 20 - 10) + 'px';
    document.body.appendChild(fly);
    setTimeout(() => fly.remove(), 800);
}

clickBtn.addEventListener('click', () => {
    if (!gameRunning) return;
    clicks++;
    updateDisplay();
    spawnFly(clickBtn);
});

startBtn.addEventListener('click', () => {
    newGame();
    startGame();
});

newGame();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
