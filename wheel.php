<?php require_once 'config.php'; requireAuth();
$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$next_day = date('Y-m-d', strtotime('+1 day'));

$today_scores = supabaseSelect('scores', [
    'select' => '*',
    'where' => "user_id=eq.$user_id&game=eq.wheel&played_at=gte.{$today}T00:00:00&played_at=lt.{$next_day}T00:00:00"
]);
$spins_today = count($today_scores);
$spins_left = max(0, $wheel_max_daily - $spins_today);

$all_wheel = supabaseSelect('scores', [
    'select' => 'points',
    'where' => "user_id=eq.$user_id&game=eq.wheel"
]);
$total_won = 0;
foreach ($all_wheel as $s) { $total_won += (int)$s['points']; }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Колесо Фортуны</title>
<link rel="stylesheet" href="style.css">
<style>
.wheel-container { text-align: center; margin: 20px 0; position: relative; }
#wheelCanvas { display: block; margin: 0 auto; }
#spinBtn { font-size: 20px; padding: 14px 40px; margin-top: 20px; }
.pointer { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); font-size: 36px; z-index: 10; filter: drop-shadow(0 0 6px rgba(255,0,0,0.8)); }
.segment-label { font-size: 12px; fill: #fff; font-weight: 700; }
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
                    <a href="pacman.php">рџ‘ѕ РџР°РєРјР°РЅ</a></div>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Магазин</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>🎡 Колесо Фортуны</h1>
        <p style="color:#888;">Крути колесо и выигрывай очки! <strong style="color:#ffd700;"><?= $spins_left ?></strong> попыток сегодня</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Попыток сегодня</span><span class="val" id="spinsLeft"><?= $spins_left ?></span></div>
            <div class="game-info-item"><span class="lbl">Всего выиграно</span><span class="val" id="totalWon"><?= $total_won ?></span></div>
            <div class="game-info-item"><span class="lbl">Последний выигрыш</span><span class="val" id="lastWin">-</span></div>
        </div>

        <div class="wheel-container">
            <div class="pointer">▼</div>
            <canvas id="wheelCanvas" width="400" height="400"></canvas>
        </div>

        <button id="spinBtn" class="btn" <?= $spins_left <= 0 ? 'disabled' : '' ?>>
            <?= $spins_left > 0 ? '🎡 Крутить!' : '❌ Попытки закончились' ?>
        </button>

        <div id="result" style="font-size:22px;font-weight:700;margin-top:16px;min-height:40px;"></div>
    </div>
</div>

<script>
const segments = <?= json_encode($wheel_segments) ?>;
const colors = ['#ff4444','#4488ff','#44cc44','#ffaa00','#cc44ff','#ff6644','#44dddd','#ff4488','#88ff44','#ffd700','#44ffaa','#ff8800'];
const canvas = document.getElementById('wheelCanvas');
const ctx = canvas.getContext('2d');
const spinBtn = document.getElementById('spinBtn');
const resultDiv = document.getElementById('result');
const spinsLeftSpan = document.getElementById('spinsLeft');
const lastWinSpan = document.getElementById('lastWin');

let spinning = false;
let currentAngle = 0;
let spinsLeft = <?= $spins_left ?>;
const segmentAngle = (Math.PI * 2) / segments.length;

function drawWheel(angle) {
    ctx.clearRect(0, 0, 400, 400);
    const cx = 200, cy = 200, r = 190;
    for (let i = 0; i < segments.length; i++) {
        const startAngle = angle + i * segmentAngle;
        const endAngle = startAngle + segmentAngle;
        ctx.beginPath();
        ctx.moveTo(cx, cy);
        ctx.arc(cx, cy, r, startAngle, endAngle);
        ctx.closePath();
        ctx.fillStyle = colors[i % colors.length];
        ctx.fill();
        ctx.strokeStyle = 'rgba(255,255,255,0.2)';
        ctx.lineWidth = 2;
        ctx.stroke();

        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(startAngle + segmentAngle / 2);
        ctx.textAlign = 'right';
        ctx.fillStyle = '#fff';
        ctx.font = 'bold 14px Inter, sans-serif';
        ctx.fillText(segments[i], r - 20, 5);
        ctx.restore();
    }
    ctx.beginPath();
    ctx.arc(cx, cy, 25, 0, Math.PI * 2);
    ctx.fillStyle = '#222';
    ctx.fill();
    ctx.strokeStyle = '#ffd700';
    ctx.lineWidth = 3;
    ctx.stroke();
}

function getResult(angle) {
    const topAngle = (-Math.PI / 2 + 2 * Math.PI) % (2 * Math.PI);
    let normalized = ((topAngle - angle) % (2 * Math.PI) + 2 * Math.PI) % (2 * Math.PI);
    const idx = Math.floor(normalized / segmentAngle) % segments.length;
    return { index: idx, value: segments[idx] };
}

function spin() {
    if (spinning || spinsLeft <= 0) return;
    spinning = true;
    spinBtn.disabled = true;
    spinBtn.textContent = '⏳ Крутится...';
    resultDiv.innerHTML = '';

    const extraSpins = 5 + Math.random() * 5;
    const targetAngle = currentAngle + extraSpins * Math.PI * 2 + Math.random() * Math.PI * 2;
    const startAngle = currentAngle;
    const duration = 3000;
    const startTime = performance.now();

    function animate(time) {
        const elapsed = time - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const ease = 1 - Math.pow(1 - progress, 3);
        currentAngle = startAngle + (targetAngle - startAngle) * ease;
        drawWheel(currentAngle);

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            currentAngle = targetAngle % (Math.PI * 2);
            const result = getResult(currentAngle);
            const points = result.value;

            if (points > 0) {
                fetch('api.php?action=save_score&game=wheel&level=1&points=' + points)
                    .then(r => r.text())
                    .then(t => {
                        resultDiv.innerHTML = '🎉 <strong style="color:#ffd700;">+' + points + '</strong> очков!';
                        spinsLeft--;
                        spinsLeftSpan.textContent = spinsLeft;
                        lastWinSpan.textContent = '+' + points;
                        if (spinsLeft <= 0) {
                            spinBtn.textContent = '❌ На сегодня всё';
                            spinBtn.disabled = true;
                        } else {
                            spinBtn.textContent = '🎡 Крутить!';
                            spinBtn.disabled = false;
                        }
                    });
            } else {
                resultDiv.innerHTML = '😔 Ничего не выпало. Повезёт в следующий раз!';
                spinsLeft--;
                spinsLeftSpan.textContent = spinsLeft;
                lastWinSpan.textContent = '0';
                if (spinsLeft <= 0) {
                    spinBtn.textContent = '❌ На сегодня всё';
                    spinBtn.disabled = true;
                } else {
                    spinBtn.textContent = '🎡 Крутить!';
                    spinBtn.disabled = false;
                }
            }
            spinning = false;
        }
    }
    requestAnimationFrame(animate);
}

spinBtn.addEventListener('click', spin);
drawWheel(0);
</script>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
