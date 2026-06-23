<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'typing', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.typing", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.typing", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>���� ������ � DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#targetText{font-size:28px;font-weight:700;color:#ffcc66;margin:20px 0;min-height:60px;letter-spacing:1px;word-break:break-word}
#userInput{width:100%;max-width:500px;padding:14px 18px;font-size:20px;text-align:center;background:rgba(30,16,4,0.85);color:#e8d5b0;border:2px solid rgba(255,136,0,0.25);border-radius:10px;font-family:inherit;outline:none;transition:border-color .3s}
#userInput:focus{border-color:#ff8800;box-shadow:0 0 20px rgba(255,136,0,0.15)}
#userInput:disabled{opacity:.5}
.status-bar{display:flex;gap:24px;justify-content:center;margin:12px 0;font-size:15px}
.status-bar span{background:rgba(40,22,5,0.6);padding:6px 16px;border-radius:8px;border:1px solid rgba(255,136,0,0.1)}
.status-bar .wpm{color:#66ddff}
.typing-area{max-width:600px;margin:0 auto}
.char-correct{color:#44dd66}
.char-wrong{color:#ff4455}
.char-pending{color:#8a7a5a}
.game-over-msg{font-size:20px;color:#ffaa33;margin:16px 0}
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
                    <a href="pacman.php">👾 Пакман</a></div><
                <a href="games.php" class="btn btn-sm">🎮 Играть</a>/div><a href="donate.php" class="btn btn-sm">💰 Донат</a><a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>?? ���� ������</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">����</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">������</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area"><div class="typing-area">
<div id="targetText">����� "����� ����" ����� ������</div>
<input type="text" id="userInput" placeholder="�������� ����� �����..." autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" disabled>
<div id="charFeedback" style="font-size:14px;min-height:24px;margin:8px 0;color:#8a7a5a"></div>
<div class="status-bar">
<span>? <span id="timerDisplay">30</span>�</span>
<span class="wpm">? <span id="wpmDisplay">0</span> ��/���</span>
<span>? <span id="correctDisplay">0</span></span>
<span>? <span id="wrongDisplay">0</span></span>
</div>
</div></div>
<div class="game-controls"><button class="btn" onclick="resetGame()">?? ����� ����</button></div>
</div></div>
<footer><p>DonateCraft � ����������� �������� ������ �� ����-����</p></footer>
<script>
const RUSSIAN_TEXTS = [
  "������ ���", "��� ����", "� ����� ���������������", "������ ������ ����",
  "������� ���������� ����", "������ �� ����", "����� ��� �������� � �����",
  "����� � ������ ������", "������� ������� �� ������", "����� ���������� �� ����",
  "������� �������� ������", "������ ����������� �����", "������ ����� �������",
  "����� ����� ��������", "����� ����� ���������", "����������� ��������� ��������",
  "������ ����", "����� ���������", "�������� � ���� �� ��������",
  "����� ������ ��� �������", "���� ��� ������ ���� ��� ������",
  "���� ����� ������ ���", "��� ���� ��� �����", "���� ����� ������ ������",
  "�� ���� ��� ������ � ���� ��� ������", "������ ���� ����� ����� ����",
  "��������� ����� ���������", "����� ������ � ���� ������",
  "��� ������� ������ ���� �� �����", "������ ������ ����� � ����� �������"
];
const GAME_TIME = 30;
let targetText = '';
let currentText = '';
let score = 0;
let correctChars = 0;
let wrongChars = 0;
let timeLeft = GAME_TIME;
let timer = null;
let gameActive = false;
let gameStarted = false;
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const targetDiv = document.getElementById('targetText');
const userInput = document.getElementById('userInput');
const charFeedback = document.getElementById('charFeedback');
const timerDisplay = document.getElementById('timerDisplay');
const wpmDisplay = document.getElementById('wpmDisplay');
const correctDisplay = document.getElementById('correctDisplay');
const wrongDisplay = document.getElementById('wrongDisplay');

function pickText() {
  return RUSSIAN_TEXTS[Math.floor(Math.random() * RUSSIAN_TEXTS.length)];
}

function resetGame() {
  clearInterval(timer);
  timer = null;
  targetText = pickText();
  currentText = '';
  score = 0;
  correctChars = 0;
  wrongChars = 0;
  timeLeft = GAME_TIME;
  gameActive = true;
  gameStarted = false;
  scoreDisplay.textContent = '0';
  timerDisplay.textContent = GAME_TIME;
  wpmDisplay.textContent = '0';
  correctDisplay.textContent = '0';
  wrongDisplay.textContent = '0';
  charFeedback.textContent = '';
  targetDiv.textContent = targetText;
  userInput.value = '';
  userInput.disabled = false;
  userInput.focus();
  renderFeedback();
}

function renderFeedback() {
  let html = '';
  for (let i = 0; i < targetText.length; i++) {
    if (i < currentText.length) {
      if (currentText[i] === targetText[i]) {
        html += '<span class="char-correct">' + targetText[i] + '</span>';
      } else {
        html += '<span class="char-wrong">' + targetText[i] + '</span>';
      }
    } else if (i === currentText.length) {
      html += '<span class="char-pending" style="border-left:2px solid #ff8800">' + targetText[i] + '</span>';
    } else {
      html += '<span class="char-pending">' + targetText[i] + '</span>';
    }
  }
  targetDiv.innerHTML = html;
}

function startTimer() {
  if (timer) return;
  gameStarted = true;
  timer = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = timeLeft;
    if (timeLeft <= 0) {
      endGame();
    }
  }, 1000);
}

function calcScore() {
  score = correctChars * 2 - wrongChars * 1;
  if (score < 0) score = 0;
  scoreDisplay.textContent = score;
}

function calcWPM() {
  const elapsed = GAME_TIME - timeLeft;
  if (elapsed > 0) {
    const wpm = Math.round((correctChars / elapsed) * 60);
    wpmDisplay.textContent = wpm;
  }
}

function endGame() {
  clearInterval(timer);
  timer = null;
  gameActive = false;
  userInput.disabled = true;
  calcScore();
  const formData = new FormData();
  formData.append('score', score);
  fetch('typing.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
    .catch(() => {});
}

userInput.addEventListener('input', () => {
  if (!gameActive) return;
  if (!gameStarted && userInput.value.length > 0) {
    startTimer();
  }
  currentText = userInput.value;
  let c = 0, w = 0;
  for (let i = 0; i < currentText.length && i < targetText.length; i++) {
    if (currentText[i] === targetText[i]) c++;
    else w++;
  }
  correctChars = c;
  wrongChars = w;
  correctDisplay.textContent = c;
  wrongDisplay.textContent = w;
  calcScore();
  calcWPM();
  charFeedback.textContent = c + ' ����������, ' + w + ' ������';
  renderFeedback();
  if (currentText.length >= targetText.length) {
    endGame();
  }
});
</script></body></html>
