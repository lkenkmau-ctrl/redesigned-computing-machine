<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'wordle', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.wordle", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.wordle", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Вордли | DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.wordle-grid { display: flex; flex-direction: column; gap: 4px; margin: 0 auto; width: fit-content; }
.wordle-row { display: flex; gap: 4px; }
.wordle-cell { width: 52px; height: 52px; border: 2px solid rgba(255,136,0,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; text-transform: uppercase; background: rgba(30,16,4,0.8); color: #e8d5b0; transition: all 0.3s; }
.wordle-cell.filled { border-color: rgba(255,136,0,0.4); }
.wordle-cell.green { background: #538d4e; border-color: #538d4e; color: #fff; }
.wordle-cell.yellow { background: #b59f3b; border-color: #b59f3b; color: #fff; }
.wordle-cell.gray { background: #3a3a3c; border-color: #3a3a3c; color: #fff; }
.wordle-input { width: 52px; height: 52px; text-align: center; font-size: 24px; font-weight: 700; text-transform: uppercase; padding: 0; margin: 0; border: 2px solid rgba(255,136,0,0.2); border-radius: 6px; background: rgba(30,16,4,0.8); color: #e8d5b0; outline: none; transition: all 0.15s; }
.wordle-input:focus { border-color: #ff8800; box-shadow: 0 0 10px rgba(255,136,0,0.2); }
.wordle-input.filled { border-color: rgba(255,136,0,0.4); }
.wordle-keyboard { display: flex; flex-direction: column; gap: 4px; margin-top: 16px; align-items: center; }
.wordle-kb-row { display: flex; gap: 4px; }
.wordle-key { min-width: 32px; height: 44px; padding: 0 8px; border: none; border-radius: 4px; background: rgba(60,40,20,0.8); color: #e8d5b0; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.1s; font-family: inherit; text-transform: uppercase; }
.wordle-key:hover { background: rgba(80,60,40,0.8); }
.wordle-key.green { background: #538d4e; color: #fff; }
.wordle-key.yellow { background: #b59f3b; color: #fff; }
.wordle-key.gray { background: #3a3a3c; color: #666; }
.wordle-key.wide { min-width: 56px; }
#statusMsg { font-size: 16px; font-weight: 600; min-height: 30px; margin: 8px 0; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link"><?= $site_name ?></a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
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
<h1>🔤 Вордли</h1>
<div class="game-info-bar">
<div class="game-info-item"><span class="lbl">Счет</span><span class="val" id="scoreDisplay">0</span></div>
<div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
<div class="game-info-item"><span class="lbl">Попытка</span><span class="val" id="attemptDisplay">1 / 6</span></div>
</div>
<div class="game-area">
<div id="gameArea"></div>
<div id="statusMsg"></div>
</div>
<div class="game-controls">
<button class="btn" onclick="submitGuess()">✅ Проверить</button>
<button class="btn" onclick="newGame()">🔄 Новая игра</button>
</div>
</div></div>
<footer><p>DonateCraft | Наслаждайся классической игрой про птичку</p></footer>
<script>
const wordBank = [
    'книга','ручка','парта','лампа','кошка','мышка','короб','карта','озеро','дверь',
    'вечер','весна','осень','город','стена','крыша','труба','печка','рыбка','птица',
    'зверь','трава','земля','ночка','песок','ветер','мороз','ложка','вилка','чашка',
    'миска','топор','игла','нитка','лента','брошь','речка','сосна','ягода','палка',
    'пятно','солнце','дерево','цветок','камень','звезда'
];

let targetWord = '';
let attempts = [];
let currentRow = 0;
let gameFinished = false;
let currentInput = '';

const rows = 6, cols = 5;

function newGame() {
    targetWord = wordBank[Math.floor(Math.random() * wordBank.length)];
    attempts = [];
    currentRow = 0;
    gameFinished = false;
    currentInput = '';
    document.getElementById('scoreDisplay').textContent = '0';
    document.getElementById('attemptDisplay').textContent = '1 / 6';
    document.getElementById('statusMsg').textContent = '';
    renderGrid();
    renderKeyboard();
}

function renderGrid() {
    const area = document.getElementById('gameArea');
    area.innerHTML = '';
    const grid = document.createElement('div');
    grid.className = 'wordle-grid';
    for (let r = 0; r < rows; r++) {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'wordle-row';
        for (let c = 0; c < cols; c++) {
            const cell = document.createElement('div');
            cell.className = 'wordle-cell';
            cell.id = 'cell_' + r + '_' + c;
            rowDiv.appendChild(cell);
        }
        grid.appendChild(rowDiv);
    }
    area.appendChild(grid);
}

function renderKeyboard() {
    const area = document.getElementById('gameArea');
    let kb = document.getElementById('wordleKeyboard');
    if (kb) kb.remove();
    kb = document.createElement('div');
    kb.id = 'wordleKeyboard';
    kb.className = 'wordle-keyboard';

    const rows = [
        ['Й','Ц','У','К','Е','Н','Г','Ш','Щ','З','Х'],
        ['Ф','Ы','В','А','П','Р','О','Л','Д','Ж','Э'],
        ['Enter','Я','Ч','С','М','И','Т','Ь','Б','Ю','Backspace']
    ];

    for (const row of rows) {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'wordle-kb-row';
        for (const key of row) {
            const btn = document.createElement('button');
            btn.className = 'wordle-key';
            if (key === 'Enter' || key === 'Backspace') btn.classList.add('wide');
            btn.textContent = key === 'Backspace' ? '⌫' : key;
            btn.dataset.key = key;
            btn.addEventListener('click', () => handleKeyClick(key));
            rowDiv.appendChild(btn);
        }
        kb.appendChild(rowDiv);
    }
    area.appendChild(kb);
}

function handleKeyClick(key) {
    if (gameFinished) return;
    if (key === 'Enter') { submitGuess(); return; }
    if (key === 'Backspace') {
        if (currentInput.length > 0) {
            currentInput = currentInput.slice(0, -1);
            updateCurrentRow();
        }
        return;
    }
    if (currentInput.length < cols) {
        currentInput += key.toLowerCase();
        updateCurrentRow();
    }
}

function updateCurrentRow() {
    for (let c = 0; c < cols; c++) {
        const cell = document.getElementById('cell_' + currentRow + '_' + c);
        cell.textContent = currentInput[c] ? currentInput[c].toUpperCase() : '';
        cell.className = 'wordle-cell' + (currentInput[c] ? ' filled' : '');
    }
}

function submitGuess() {
    if (gameFinished) return;
    if (currentInput.length !== cols) {
        document.getElementById('statusMsg').textContent = '❌ Введите 5 букв';
        return;
    }
    if (!wordBank.includes(currentInput)) {
        document.getElementById('statusMsg').textContent = '❌ Нет такого слова в списке';
        return;
    }

    const guess = currentInput;
    const target = targetWord;
    attempts.push(guess);

    const result = [];
    const targetArr = target.split('');
    const guessArr = guess.split('');
    const used = Array(cols).fill(false);

    for (let i = 0; i < cols; i++) {
        if (guessArr[i] === targetArr[i]) {
            result[i] = 'green';
            used[i] = true;
        }
    }
    for (let i = 0; i < cols; i++) {
        if (result[i]) continue;
        const idx = targetArr.findIndex((ch, j) => !used[j] && ch === guessArr[i]);
        if (idx !== -1) {
            result[i] = 'yellow';
            used[idx] = true;
        } else {
            result[i] = 'gray';
        }
    }

    for (let i = 0; i < cols; i++) {
        const cell = document.getElementById('cell_' + currentRow + '_' + i);
        cell.className = 'wordle-cell ' + result[i];
        cell.textContent = guess[i].toUpperCase();
        const keys = document.querySelectorAll('.wordle-key');
        keys.forEach(k => {
            if (k.dataset.key.toLowerCase() === guess[i]) {
                if (result[i] === 'green') k.className = 'wordle-key green';
                else if (result[i] === 'yellow' && !k.classList.contains('green')) k.className = 'wordle-key yellow';
                else if (result[i] === 'gray' && !k.classList.contains('green') && !k.classList.contains('yellow')) k.className = 'wordle-key gray';
            }
        });
    }

    const isWin = result.every(r => r === 'green');
    currentRow++;
    currentInput = '';

    if (isWin) {
        gameFinished = true;
        const score = (7 - currentRow) * 100;
        document.getElementById('scoreDisplay').textContent = score;
        document.getElementById('statusMsg').textContent = '🎉 Угадал! Слово: ' + target.toUpperCase() + '. Счет: ' + score;
        const formData = new FormData();
        formData.append('score', score);
        fetch('wordle.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) document.getElementById('bestDisplay').textContent = data.best; })
            .catch(() => {});
    } else if (currentRow >= 6) {
        gameFinished = true;
        document.getElementById('statusMsg').textContent = '💀 Проигрыш! Слово: ' + target.toUpperCase();
        const formData = new FormData();
        formData.append('score', 0);
        fetch('wordle.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) document.getElementById('bestDisplay').textContent = data.best; })
            .catch(() => {});
    } else {
        document.getElementById('attemptDisplay').textContent = (currentRow + 1) + ' / 6';
        document.getElementById('statusMsg').textContent = '';
    }
}

document.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); submitGuess(); return; }
    if (e.key === 'Backspace') { e.preventDefault(); handleKeyClick('Backspace'); return; }
    const key = e.key.toLowerCase();
    if (/^[а-яё]$/i.test(key)) handleKeyClick(key);
});

newGame();
</script></body></html>