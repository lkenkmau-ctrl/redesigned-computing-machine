<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Угадай число</title>
<link rel="stylesheet" href="style.css">
<style>
.guess-area { max-width: 500px; margin: 20px auto; }
.guess-display { font-size: 56px; font-weight: 800; color: #00ff00; text-shadow: 0 0 30px rgba(0,255,0,0.3); margin: 20px 0; min-height: 70px; }
.guess-input { width: 120px; text-align: center; font-size: 24px; font-weight: 700; margin: 0 auto 16px; display: block; }
.guess-hint { font-size: 20px; font-weight: 600; min-height: 40px; margin: 12px 0; }
.guess-attempts { color: #888; font-size: 14px; }
.guess-history { display: flex; gap: 6px; justify-content: center; flex-wrap: wrap; margin: 12px 0; }
.guess-history span { background: rgba(0,170,0,0.1); border: 1px solid rgba(0,170,0,0.2); border-radius: 6px; padding: 4px 10px; font-size: 14px; color: #aaa; }
.guess-history span.low { border-color: rgba(255,100,100,0.3); color: #ff6666; }
.guess-history span.high { border-color: rgba(100,100,255,0.3); color: #6688ff; }
.guess-history span.correct { border-color: rgba(0,255,0,0.5); color: #00ff00; font-weight: 700; }
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
        <h1>🔢 Угадай число</h1>
        <p style="color:#888;margin-bottom:16px;">Я загадал число от 1 до 100. Попробуй угадать!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Попыток</span><span class="val" id="attemptsDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Осталось</span><span class="val" id="remainingDisplay">10</span></div>
            <div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">100</span></div>
        </div>

        <div class="guess-area">
            <div class="guess-display" id="guessDisplay">?</div>
            <input type="number" class="guess-input" id="guessInput" min="1" max="100" placeholder="1-100" autofocus>
            <div class="guess-hint" id="hintDisplay"></div>
            <div class="guess-history" id="historyDisplay"></div>
            <div class="guess-attempts" id="attemptsInfo"></div>
            <div class="game-controls">
                <button id="guessBtn" class="btn">✅ Проверить</button>
                <button id="newGameBtn" class="btn btn-outline">🔄 Новая игра</button>
            </div>
            <div id="result" style="font-size:18px;font-weight:600;min-height:30px;margin-top:12px;"></div>
        </div>
    </div>
</div>

<script>
const guessInput = document.getElementById('guessInput');
const guessBtn = document.getElementById('guessBtn');
const newGameBtn = document.getElementById('newGameBtn');
const guessDisplay = document.getElementById('guessDisplay');
const hintDisplay = document.getElementById('hintDisplay');
const attemptsDisplay = document.getElementById('attemptsDisplay');
const remainingDisplay = document.getElementById('remainingDisplay');
const scoreDisplay = document.getElementById('scoreDisplay');
const historyDisplay = document.getElementById('historyDisplay');
const attemptsInfo = document.getElementById('attemptsInfo');
const resultDiv = document.getElementById('result');

let target, attempts, maxAttempts = 10, gameOver, saved, history;

function newGame() {
    target = Math.floor(Math.random() * 100) + 1;
    attempts = 0;
    gameOver = false;
    saved = false;
    history = [];
    guessInput.value = '';
    guessInput.disabled = false;
    guessBtn.disabled = false;
    guessDisplay.textContent = '?';
    guessDisplay.style.color = '#00ff00';
    hintDisplay.innerHTML = '';
    resultDiv.innerHTML = '';
    historyDisplay.innerHTML = '';
    guessInput.focus();
    updateStats();
}

function updateStats() {
    let remaining = Math.max(0, maxAttempts - attempts);
    let score = Math.max(0, 100 - attempts * 10);
    attemptsDisplay.textContent = attempts;
    remainingDisplay.textContent = remaining;
    scoreDisplay.textContent = score;
}

function addHistory(guess, result) {
    history.push({guess, result});
    historyDisplay.innerHTML = history.map(h =>
        `<span class="${h.result}">${h.guess}</span>`
    ).join('');
}

guessBtn.addEventListener('click', () => {
    if (gameOver) return;
    let val = parseInt(guessInput.value);
    if (isNaN(val) || val < 1 || val > 100) {
        hintDisplay.innerHTML = '<span style="color:#ff6666;">Введите число от 1 до 100</span>';
        return;
    }
    attempts++;
    let remaining = maxAttempts - attempts;

    if (val === target) {
        gameOver = true;
        guessInput.disabled = true;
        guessBtn.disabled = true;
        guessDisplay.textContent = target;
        guessDisplay.style.color = '#ffd700';
        addHistory(val, 'correct');
        let score = Math.max(0, 100 - attempts * 10);
        hintDisplay.innerHTML = '🎉 <strong style="color:#00ff00;">Правильно!</strong> Это было число ' + target;

        if (!saved) {
            saved = true;
            fetch('api.php?action=save_score&game=guess&level=1&points=' + score)
                .then(r => r.text())
                .then(t => {
                    resultDiv.innerHTML = '✅ +<strong style="color:#ffd700;">' + score + '</strong> очков зачислено!';
                })
                .catch(() => {
                    resultDiv.innerHTML = '⚠️ Ошибка сохранения.';
                });
        }
        updateStats();
        return;
    }

    if (val < target) {
        hintDisplay.innerHTML = '🔺 <strong>Больше!</strong> Загаданное число больше <strong style="color:#ff6666;">' + val + '</strong>';
        addHistory(val, 'low');
    } else {
        hintDisplay.innerHTML = '🔻 <strong>Меньше!</strong> Загаданное число меньше <strong style="color:#6688ff;">' + val + '</strong>';
        addHistory(val, 'high');
    }

    if (remaining <= 0) {
        gameOver = true;
        guessInput.disabled = true;
        guessBtn.disabled = true;
        guessDisplay.textContent = target;
        hintDisplay.innerHTML = '😔 Попытки закончились. Было загадано <strong style="color:#ffd700;">' + target + '</strong>';
        resultDiv.innerHTML = '💡 Начни новую игру!';
    }

    guessInput.value = '';
    guessInput.focus();
    updateStats();
});

guessInput.addEventListener('keydown', e => {
    if (e.key === 'Enter') guessBtn.click();
});

newGameBtn.addEventListener('click', newGame);

newGame();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
