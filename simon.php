<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'simon', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.simon", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.simon", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Саймон | DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.simon-grid { display: grid; grid-template-columns: 110px 110px; gap: 8px; margin: 10px auto; justify-content: center; }
.simon-btn { width: 110px; height: 110px; border-radius: 16px; cursor: pointer; transition: all 0.12s; border: 3px solid rgba(0,0,0,0.3); }
.simon-btn:active { transform: scale(0.92); }
.simon-btn.active { filter: brightness(1.5); transform: scale(1.04); }
#btn-red { background: #cc0000; } #btn-red.active { background: #ff4444; box-shadow: 0 0 30px #ff4444; }
#btn-blue { background: #0044cc; } #btn-blue.active { background: #4488ff; box-shadow: 0 0 30px #4488ff; }
#btn-green { background: #00aa00; } #btn-green.active { background: #44ff44; box-shadow: 0 0 30px #44ff44; }
#btn-yellow { background: #ccaa00; } #btn-yellow.active { background: #ffee44; box-shadow: 0 0 30px #ffee44; }
.simon-label { display: flex; gap: 6px; justify-content: center; }
.simon-label span { font-size: 12px; color: #8a7a5a; width: 110px; text-align: center; }
.game-message { font-size: 18px; font-weight: 600; min-height: 28px; margin: 10px 0; color: #ffaa33; }
.round-display { font-size: 16px; color: #ffcc66; margin: 8px 0; }
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
                    <a href="pacman.php">👾 Пакман</a></div>

                <a href="games.php" class="btn btn-sm">🎮 Играть</a>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Донат</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container"><div class="game-wrapper">
<h1>🔴 Саймон</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Раунд</span><span class="val" id="roundDisplay">0</span></div><div class="game-info-item"><span class="lbl">Счет</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area">
<div><div class="simon-grid">
<div id="btn-red" class="simon-btn" data-color="0"></div>
<div id="btn-blue" class="simon-btn" data-color="1"></div>
<div id="btn-green" class="simon-btn" data-color="2"></div>
<div id="btn-yellow" class="simon-btn" data-color="3"></div>
</div>
<div class="simon-label"><span>Красный</span><span>Синий</span><span>Зелёный</span><span>Жёлтый</span></div></div>
</div>
<div id="gameMessage" class="game-message"></div>
<div class="game-controls"><button class="btn" onclick="startGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft | Наслаждайся классической игрой про птичку</p></footer>
<script>
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const roundDisplay = document.getElementById('roundDisplay');
const gameMessage = document.getElementById('gameMessage');
const COLORS = ['red', 'blue', 'green', 'yellow'];
const BTN_IDS = ['btn-red', 'btn-blue', 'btn-green', 'btn-yellow'];
let sequence = [];
let playerStep = 0;
let round = 0;
let score = 0;
let isPlaying = false;
let acceptingInput = false;
let gameOver = false;
let scoreSubmitted = false;

function flashBtn(index) {
    const el = document.getElementById(BTN_IDS[index]);
    el.classList.add('active');
    setTimeout(() => el.classList.remove('active'), 400);
}

function playSequence(callback) {
    isPlaying = true;
    acceptingInput = false;
    let i = 0;
    function showNext() {
        if (i >= sequence.length) {
            isPlaying = false;
            acceptingInput = true;
            playerStep = 0;
            if (callback) callback();
            return;
        }
        flashBtn(sequence[i]);
        i++;
        setTimeout(showNext, 600);
    }
    setTimeout(showNext, 300);
}

function nextRound() {
    round++;
    sequence.push(Math.floor(Math.random() * 4));
    roundDisplay.textContent = round;
    score = round * 50;
    scoreDisplay.textContent = score;
    gameMessage.textContent = 'Раунд ' + round + ' — запомни последовательность!';
    playSequence(() => {
        gameMessage.textContent = 'Твой ход — повтори!';
    });
}

function playerClick(colorIndex) {
    if (!acceptingInput || gameOver) return;
    flashBtn(colorIndex);
    if (colorIndex !== sequence[playerStep]) {
        endGame();
        return;
    }
    playerStep++;
    if (playerStep >= sequence.length) {
        acceptingInput = false;
        setTimeout(nextRound, 600);
    }
}

for (let i = 0; i < 4; i++) {
    document.getElementById(BTN_IDS[i]).addEventListener('click', () => playerClick(i));
}

function endGame() {
    gameOver = true;
    acceptingInput = false;
    isPlaying = false;
    const finalScore = Math.max(0, (round - 1) * 50);
    scoreDisplay.textContent = finalScore;
    gameMessage.textContent = '💀 Ошибка! Ты продержался ' + round + ' раундов. Счет: ' + finalScore;
    if (scoreSubmitted) return;
    scoreSubmitted = true;
    const formData = new FormData();
    formData.append('score', finalScore);
    fetch('simon.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function startGame() {
    gameOver = false;
    scoreSubmitted = false;
    sequence = [];
    playerStep = 0;
    round = 0;
    score = 0;
    acceptingInput = false;
    isPlaying = false;
    roundDisplay.textContent = '0';
    scoreDisplay.textContent = '0';
    gameMessage.textContent = 'Начинаем!';
    setTimeout(nextRound, 500);
}
</script></body></html>