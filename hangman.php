<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'hangman', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.hangman", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.hangman", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Виселица — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
canvas { border: 2px solid rgba(255,136,0,0.2); background: #0a0500; border-radius: 8px; }
.word-display { font-size: 32px; font-weight: 700; letter-spacing: 10px; font-family: monospace; margin: 16px 0; color: #ffcc66; }
.letter-grid { display: flex; flex-wrap: wrap; gap: 4px; max-width: 350px; justify-content: center; margin: 10px auto; }
.letter-btn { width: 36px; height: 36px; border: 1px solid rgba(255,136,0,0.25); background: rgba(255,136,0,0.08); color: #e8d5b0; border-radius: 6px; cursor: pointer; font-size: 15px; font-weight: 600; transition: all 0.15s; font-family: inherit; }
.letter-btn:hover { background: rgba(255,136,0,0.2); border-color: #ff8800; }
.letter-btn.used { opacity: 0.3; cursor: not-allowed; }
.letter-btn.used-correct { background: rgba(0,200,0,0.2); border-color: #00cc00; color: #44ff44; opacity: 0.7; }
.letter-btn.used-wrong { background: rgba(200,0,0,0.2); border-color: #cc0000; color: #ff4444; opacity: 0.7; }
.hint-row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin: 8px 0; font-size: 14px; color: #8a7a5a; }
.hint-row span { background: rgba(0,0,0,0.3); padding: 4px 12px; border-radius: 8px; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
<a href="snake.php">?? ������</a>
<a href="tetris.php">?? ������</a>
<a href="2048.php">?? 2048</a>
<a href="tictactoe.php">? ��������-������</a>
<a href="guess.php">? ������ �����</a>
<a href="memory.php">?? ������</a>
<a href="clicker.php">?? ������</a>
<a href="quiz.php">?? ���������</a>
<a href="flappy.php">?? Flappy Bird</a>
<a href="reaction.php">? Reaction Test</a>
<a href="minesweeper.php">?? ����</a>
<a href="hangman.php">?? ��������</a>
<a href="simon.php">?? ������</a>
<a href="pong.php">?? ����</a>
<a href="invaders.php">?? ���������</a>
<a href="breakout.php">?? ��������</a>
<a href="sudoku.php">?? ������</a>
<a href="wordle.php">?? ������</a>
<a href="dino.php">?? ����������</a>
<a href="rps.php">? ������-�������</a>
<a href="typing.php">?? ������</a>
<a href="color_match.php">?? ����</a>
<a href="balloon.php">?? ������</a>
<a href="whack.php">?? ����</a>
<a href="hanoi.php">?? �����</a>
<a href="connect4.php">?? 4 � ���</a>
<a href="math.php">?? ����������</a>
<a href="fifteen.php">?? ��������</a>
<a href="asteroids.php">?? ���������</a>
<a href="pacman.php">?? ������</a>
</div></div><a href="donate.php" class="btn btn-sm">💰 Магазин</a><a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>👻 Виселица</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area">
<canvas id="hangmanCanvas" width="300" height="360"></canvas>
<div><div id="wordDisplay" class="word-display">_</div>
<div class="hint-row"><span id="hintLength">Длина: 0</span><span id="hintWrong">Ошибок: 0/6</span></div>
<div id="letterGrid" class="letter-grid"></div>
<div id="gameMessage" style="font-size:16px;font-weight:600;min-height:24px;margin:8px 0;color:#ffaa33;"></div></div>
</div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const canvas = document.getElementById('hangmanCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const wordDisplay = document.getElementById('wordDisplay');
const letterGrid = document.getElementById('letterGrid');
const gameMessage = document.getElementById('gameMessage');
const hintLength = document.getElementById('hintLength');
const hintWrong = document.getElementById('hintWrong');

const WORDS = ['программирование','разработка','компьютер','сервер','администратор','пользователь','интерфейс','алгоритм','функция','переменная','класс','объект','массив','строка','число','цикл','условие','оператор','команда','проект','система','приложение','модуль','пакет','библиотека','фреймворк','шаблон','событие','обработка','запрос','ответ','сессия','авторизация','регистрация','пароль','логин','почта','модератор','разработчик','тестировщик','документация','репозиторий','анимация','графика','механизм','конструкция','навигация','коллекция','итерация','контейнер','индексация'];
const MAX_WRONG = 6;
let word, guessed, wrongCount, gameOver, scoreSubmitted;

const ALPHABET = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя'.split('');

function selectWord() {
    word = WORDS[Math.floor(Math.random() * WORDS.length)].toLowerCase();
}

function createLetterButtons() {
    letterGrid.innerHTML = '';
    for (const letter of ALPHABET) {
        const btn = document.createElement('button');
        btn.className = 'letter-btn';
        btn.textContent = letter.toUpperCase();
        btn.dataset.letter = letter;
        btn.addEventListener('click', () => guessLetter(letter, btn));
        letterGrid.appendChild(btn);
    }
}

function guessLetter(letter, btn) {
    if (gameOver || btn.classList.contains('used')) return;
    btn.classList.add('used');
    if (word.includes(letter)) {
        btn.classList.add('used-correct');
        for (let i = 0; i < word.length; i++)
            if (word[i] === letter) guessed[i] = letter;
        updateWordDisplay();
        if (!guessed.includes('_')) endGame(true);
    } else {
        btn.classList.add('used-wrong');
        wrongCount++;
        hintWrong.textContent = 'Ошибок: ' + wrongCount + '/6';
        drawHangman();
        if (wrongCount >= MAX_WRONG) endGame(false);
    }
    updateScore();
}

function updateWordDisplay() {
    wordDisplay.textContent = guessed.join(' ');
}

function updateScore() {
    const correct = guessed.filter((l, i) => l !== '_' && word[i] === l).length;
    const score = Math.max(0, correct * 10 - wrongCount * 5);
    scoreDisplay.textContent = score;
}

function drawHangman() {
    ctx.fillStyle = '#0a0500';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.strokeStyle = '#ff8800';
    ctx.lineWidth = 3;
    // Gallows
    ctx.beginPath();
    ctx.moveTo(40, 340); ctx.lineTo(180, 340);
    ctx.moveTo(80, 340); ctx.lineTo(80, 40);
    ctx.moveTo(80, 40); ctx.lineTo(200, 40);
    ctx.moveTo(200, 40); ctx.lineTo(200, 70);
    ctx.stroke();
    // Rope
    ctx.beginPath();
    ctx.moveTo(196, 70); ctx.lineTo(204, 70);
    ctx.stroke();
    ctx.lineWidth = 2;
    if (wrongCount >= 1) {
        ctx.beginPath(); ctx.arc(200, 100, 25, 0, Math.PI * 2); ctx.stroke();
    }
    if (wrongCount >= 2) {
        ctx.beginPath(); ctx.moveTo(200, 125); ctx.lineTo(200, 200); ctx.stroke();
    }
    if (wrongCount >= 3) {
        ctx.beginPath(); ctx.moveTo(200, 140); ctx.lineTo(160, 180); ctx.stroke();
    }
    if (wrongCount >= 4) {
        ctx.beginPath(); ctx.moveTo(200, 140); ctx.lineTo(240, 180); ctx.stroke();
    }
    if (wrongCount >= 5) {
        ctx.beginPath(); ctx.moveTo(200, 200); ctx.lineTo(160, 260); ctx.stroke();
    }
    if (wrongCount >= 6) {
        ctx.beginPath(); ctx.moveTo(200, 200); ctx.lineTo(240, 260); ctx.stroke();
    }
}

function endGame(won) {
    gameOver = true;
    const correct = guessed.filter((l, i) => l !== '_' && word[i] === l).length;
    const finalScore = Math.max(0, correct * 10 - wrongCount * 5);
    scoreDisplay.textContent = finalScore;
    gameMessage.textContent = won ? '🎉 Победа! Вы угадали слово: ' + word.toUpperCase() : '💀 Вы проиграли. Слово: ' + word.toUpperCase();
    if (scoreSubmitted) return;
    scoreSubmitted = true;
    const formData = new FormData();
    formData.append('score', finalScore);
    fetch('hangman.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function resetGame() {
    gameOver = false;
    wrongCount = 0;
    scoreSubmitted = false;
    guessed = [];
    selectWord();
    for (let i = 0; i < word.length; i++) guessed.push('_');
    hintLength.textContent = 'Длина: ' + word.length;
    hintWrong.textContent = 'Ошибок: 0/6';
    updateWordDisplay();
    gameMessage.textContent = '';
    scoreDisplay.textContent = '0';
    createLetterButtons();
    drawHangman();
}

resetGame();
</script></body></html>
