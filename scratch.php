<?php require_once 'config.php'; requireAuth();
$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$next_day = date('Y-m-d', strtotime('+1 day'));

$today_scores = supabaseSelect('scores', [
    'select' => '*',
    'where' => "user_id=eq.$user_id&game=eq.scratch&played_at=gte.{$today}T00:00:00&played_at=lt.{$next_day}T00:00:00"
]);
$cards_today = count($today_scores);
$cards_left = max(0, $scratch_max_daily - $cards_today);

$all_scratch = supabaseSelect('scores', [
    'select' => 'points',
    'where' => "user_id=eq.$user_id&game=eq.scratch"
]);
$total_won = 0;
foreach ($all_scratch as $s) { $total_won += (int)$s['points']; }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>РЎРєСЂРµС‚С‡ РљР°СЂС‚Р°</title>
<link rel="stylesheet" href="style.css">
<style>
.scratch-card-wrap { text-align: center; margin: 20px 0; position: relative; }
#scratchCanvas {
    display: block; margin: 0 auto; border-radius: 16px;
    cursor: crosshair; touch-action: none;
    background: linear-gradient(135deg, #1a1a2e, #2a1a3e);
    border: 2px solid rgba(255,215,0,0.3);
    box-shadow: 0 0 30px rgba(255,215,0,0.1);
}
.card-prize-text {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
    font-size: 48px; font-weight: 900; color: #ffd700;
    text-shadow: 0 0 20px rgba(255,215,0,0.3);
    pointer-events: none; z-index: 0;
}
.scratch-overlay {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    border-radius: 16px;
}
#progressBar {
    width: 200px; height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; margin: 10px auto; overflow: hidden;
}
#progressFill { height: 100%; width: 0%; background: linear-gradient(90deg, #ffd700, #ffaa00); border-radius: 3px; transition: width 0.2s; }
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
        <h1>рџЋ° РЎРєСЂРµС‚С‡ РљР°СЂС‚Р°</h1>
        <p style="color:#888;">РЎС‚РёСЂР°Р№ РїРѕРєСЂС‹С‚РёРµ РјС‹С€РєРѕР№ Рё Р·Р°Р±РµСЂРё РїСЂРёР·! <strong style="color:#ffd700;"><?= $cards_left ?></strong> РєР°СЂС‚ СЃРµРіРѕРґРЅСЏ</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">РљР°СЂС‚ СЃРµРіРѕРґРЅСЏ</span><span class="val" id="cardsLeft"><?= $cards_left ?></span></div>
            <div class="game-info-item"><span class="lbl">Р’СЃРµРіРѕ РІС‹РёРіСЂР°РЅРѕ</span><span class="val" id="totalWon"><?= $total_won ?></span></div>
            <div class="game-info-item"><span class="lbl">РџСЂРёР·</span><span class="val" id="lastWin">-</span></div>
        </div>

        <div class="scratch-card-wrap" id="scratchWrap" style="position:relative;">
            <div id="prizeText" class="card-prize-text">?</div>
            <canvas id="scratchCanvas" width="320" height="240"></canvas>
        </div>

        <div id="progressBar"><div id="progressFill"></div></div>

        <div class="game-controls">
            <button id="newCardBtn" class="btn" <?= $cards_left <= 0 ? 'disabled' : '' ?>>
                <?= $cards_left > 0 ? 'рџѓЏ РќРѕРІР°СЏ РєР°СЂС‚Р°' : 'вќЊ РљР°СЂС‚С‹ Р·Р°РєРѕРЅС‡РёР»РёСЃСЊ' ?>
            </button>
        </div>

        <div id="result" style="font-size:22px;font-weight:700;margin-top:16px;min-height:40px;"></div>
    </div>
</div>

<script>
const prizes = [0, 50, 100, 200, 300, 500];
const canvas = document.getElementById('scratchCanvas');
const ctx = canvas.getContext('2d');
    const prizeText = document.getElementById('prizeText');
const progressFill = document.getElementById('progressFill');
const resultDiv = document.getElementById('result');
const newCardBtn = document.getElementById('newCardBtn');
const cardsLeftSpan = document.getElementById('cardsLeft');
const lastWinSpan = document.getElementById('lastWin');

let cardsLeft = <?= $cards_left ?>;
let isScratching = false;
let revealed = false;
let savedPrize = false;
let currentPrize = 0;
let totalPixels = 320 * 240;
let lastX = -1, lastY = -1;

function initCard() {
    currentPrize = prizes[Math.floor(Math.random() * prizes.length)];
    revealed = false;
    savedPrize = false;
    progressFill.style.width = '0%';
    resultDiv.innerHTML = '';
    prizeText.textContent = currentPrize > 0 ? '+' + currentPrize : '0';
    prizeText.style.opacity = '0.3';
    lastX = -1; lastY = -1;
    drawCoating();
}

function drawCoating() {
    ctx.fillStyle = '#b0b0b0';
    ctx.fillRect(0, 0, 320, 240);

    ctx.fillStyle = '#c0c0c0';
    for (let y = 0; y < 240; y += 6) {
        ctx.fillRect(0, y, 320, 3);
    }

    ctx.fillStyle = 'rgba(255,255,255,0.08)';
    for (let i = 0; i < 30; i++) {
        let x = Math.random() * 320, y = Math.random() * 240;
        ctx.beginPath();
        ctx.arc(x, y, 1 + Math.random() * 3, 0, Math.PI * 2);
        ctx.fill();
    }

    ctx.fillStyle = 'rgba(180,160,130,0.4)';
    ctx.font = 'bold 48px sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('рџЋ°', 160, 120);
}

function scratch(x, y) {
    if (revealed || cardsLeft <= 0) return;
    ctx.globalCompositeOperation = 'destination-out';
    ctx.lineWidth = 36;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    if (lastX >= 0 && lastY >= 0) {
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.stroke();
    } else {
        ctx.beginPath();
        ctx.arc(x, y, 18, 0, Math.PI * 2);
        ctx.fill();
    }
    lastX = x; lastY = y;
    ctx.globalCompositeOperation = 'source-over';

    let imageData = ctx.getImageData(0, 0, 320, 240);
    let transparent = 0;
    for (let i = 3; i < imageData.data.length; i += 4) {
        if (imageData.data[i] === 0) transparent++;
    }
    let percent = (transparent / totalPixels) * 100;
    progressFill.style.width = Math.min(percent, 100) + '%';

    if (percent >= 40 && !revealed) {
        revealPrize();
    }
}

function revealPrize() {
    revealed = true;
    ctx.clearRect(0, 0, 320, 240);
    prizeText.style.opacity = '1';

    if (!savedPrize && cardsLeft > 0) {
        savedPrize = true;
        if (currentPrize > 0) {
            fetch('api.php?action=save_score&game=scratch&level=1&points=' + currentPrize)
                .then(r => r.text())
                .then(t => {
                    resultDiv.innerHTML = 'рџЋ‰ <strong style="color:#ffd700;">+' + currentPrize + '</strong> РѕС‡РєРѕРІ!';
                    lastWinSpan.textContent = '+' + currentPrize;
                });
        } else {
            resultDiv.innerHTML = 'рџ” Р’ СЌС‚РѕС‚ СЂР°Р· РЅРёС‡РµРіРѕ. РџРѕРІРµР·С‘С‚ РІ СЃР»РµРґСѓСЋС‰РёР№ СЂР°Р·!';
        }
    }

    setTimeout(() => {
        cardsLeft--;
        cardsLeftSpan.textContent = cardsLeft;
        if (cardsLeft <= 0) {
            newCardBtn.textContent = 'вќЊ РќР° СЃРµРіРѕРґРЅСЏ РІСЃС‘';
            newCardBtn.disabled = true;
        } else {
            newCardBtn.disabled = false;
        }
    }, 500);
}

canvas.addEventListener('mousedown', e => { isScratching = true; lastX = -1; scratch(e.offsetX, e.offsetY); });
canvas.addEventListener('mousemove', e => { if (isScratching) scratch(e.offsetX, e.offsetY); });
canvas.addEventListener('mouseup', () => { isScratching = false; lastX = -1; });
canvas.addEventListener('mouseleave', () => { isScratching = false; lastX = -1; });
canvas.addEventListener('touchstart', e => {
    e.preventDefault();
    let rect = canvas.getBoundingClientRect();
    let touch = e.touches[0];
    isScratching = true;
    lastX = -1;
    scratch(touch.clientX - rect.left, touch.clientY - rect.top);
});
canvas.addEventListener('touchmove', e => {
    e.preventDefault();
    if (!isScratching) return;
    let rect = canvas.getBoundingClientRect();
    let touch = e.touches[0];
    scratch(touch.clientX - rect.left, touch.clientY - rect.top);
});
canvas.addEventListener('touchend', () => { isScratching = false; lastX = -1; });

newCardBtn.addEventListener('click', () => {
    if (cardsLeft <= 0) return;
    newCardBtn.disabled = true;
    initCard();
});

initCard();
</script>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
