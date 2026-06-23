<?php
require_once 'config.php';
requireAuth();
$page_title = "Reaction Test";
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time'])) {
    $time = (float)$_POST['time'];
    supabaseInsert('game_scores', [
        'user_id' => $user_id,
        'game' => 'reaction',
        'score' => $time,
        'created_at' => date('c')
    ]);
    $bests = supabaseSelect('game_scores', [
        'select' => 'score',
        'where' => "user_id=eq.$user_id&game=eq.reaction",
        'order' => 'score.asc',
        'limit' => 1
    ]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]);
    exit;
}

$bestData = supabaseSelect('game_scores', [
    'select' => 'score',
    'where' => "user_id=eq.$user_id&game=eq.reaction",
    'order' => 'score.asc',
    'limit' => 1
]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reaction Test — DonateCraft</title>
    <link rel="stylesheet" href="style.css">
    <style>
    #reactionBox {
        width: 300px; height: 300px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; font-weight: 700; cursor: pointer;
        margin: 20px auto; user-select: none;
        transition: background 0.1s, transform 0.1s;
        border: 2px solid rgba(255,136,0,0.2);
    }
    #reactionBox.waiting { background: #cc3300; color: #fff; }
    #reactionBox.ready { background: #00aa00; color: #fff; }
    #reactionBox.too-early { background: #880000; color: #fff; }
    #reactionBox.idle { background: rgba(40,22,5,0.6); color: #8a7a5a; }
    #reactionBox:hover { transform: scale(1.02); }
    .reaction-results { text-align: center; margin: 16px 0; }
    .reaction-results .last { font-size: 28px; font-weight: 800; color: #ff9900; }
    .reaction-results .best { font-size: 18px; color: #ffd700; }
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
                </div>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Магазин</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper">
        <h1>⚡ Reaction Test</h1>
        <p style="color:#8a7a5a;margin-bottom:10px;">Нажми на квадрат, когда он станет зелёным!</p>
        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Лучшее</span><span class="val" id="bestDisplay"><?= $bestScore ? $bestScore . 'ms' : '—' ?></span></div>
            <div class="game-info-item"><span class="lbl">Попыток</span><span class="val" id="attemptsDisplay">0</span></div>
        </div>
        <div id="reactionBox" class="idle">Нажми, чтобы начать</div>
        <div class="reaction-results">
            <div class="last" id="lastDisplay">—</div>
            <div class="best" id="bestDisplay2"></div>
        </div>
        <div class="game-controls">
            <button class="btn" onclick="resetTest()">🔄 Сбросить</button>
        </div>
    </div>
</div>
<footer>
    <p>DonateCraft — зарабатывай донатные поинты за мини-игры</p>
</footer>
<script>
const box = document.getElementById('reactionBox');
const lastDisplay = document.getElementById('lastDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const bestDisplay2 = document.getElementById('bestDisplay2');
const attemptsDisplay = document.getElementById('attemptsDisplay');

let state = 'idle'; // idle | waiting | ready | too-early
let timeoutId = null;
let startTime = 0;
let attempts = 0;
let best = <?= $bestScore ?: 'null' ?>;

function resetTest() {
    if (timeoutId) { clearTimeout(timeoutId); timeoutId = null; }
    state = 'idle';
    box.className = 'idle';
    box.textContent = 'Нажми, чтобы начать';
}

function startWait() {
    state = 'waiting';
    box.className = 'waiting';
    box.textContent = 'Жди...';
    const delay = 1000 + Math.random() * 3000;
    timeoutId = setTimeout(() => {
        state = 'ready';
        box.className = 'ready';
        box.textContent = 'ЖМИ!';
        startTime = performance.now();
        timeoutId = null;
    }, delay);
}

function submitScore(time) {
    const formData = new FormData();
    formData.append('time', time);
    fetch('reaction.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.best && data.best > 0) {
                best = data.best;
                bestDisplay.textContent = best + 'ms';
                bestDisplay2.textContent = '🏆 Рекорд: ' + best + 'ms';
            }
        })
        .catch(() => {});
}

box.addEventListener('click', () => {
    if (state === 'idle') {
        attempts = 0;
        attemptsDisplay.textContent = attempts;
        lastDisplay.textContent = '—';
        startWait();
        return;
    }
    if (state === 'waiting') {
        if (timeoutId) { clearTimeout(timeoutId); timeoutId = null; }
        state = 'too-early';
        box.className = 'too-early';
        box.textContent = 'Слишком рано! Нажми чтобы начать заново';
        return;
    }
    if (state === 'too-early') {
        resetTest();
        return;
    }
    if (state === 'ready') {
        const elapsed = performance.now() - startTime;
        attempts++;
        attemptsDisplay.textContent = attempts;
        lastDisplay.textContent = elapsed.toFixed(0) + ' ms';
        if (best === null || elapsed < best) {
            best = elapsed;
            bestDisplay.textContent = best.toFixed(0) + 'ms';
            bestDisplay2.textContent = '🏆 Новый рекорд! ' + best.toFixed(0) + 'ms';
        } else {
            bestDisplay2.textContent = '🏆 Рекорд: ' + best.toFixed(0) + 'ms';
        }
        submitScore(elapsed.toFixed(0));
        state = 'idle';
        box.className = 'idle';
        box.textContent = 'Ещё раз! Нажми';
        return;
    }
});
</script>
</body>
</html>
