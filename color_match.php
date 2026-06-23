<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'color_match', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.color_match", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.color_match", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Цветовая реакция — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.color-word{font-size:60px;font-weight:800;margin:30px 0;min-height:80px;text-shadow:0 0 30px rgba(255,255,255,0.08);transition:all .3s}
.color-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin:20px 0}
.color-btn{width:90px;height:90px;border-radius:16px;border:3px solid transparent;cursor:pointer;transition:all .2s;position:relative;font-size:13px;font-weight:600;color:rgba(255,255,255,0.85);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px}
.color-btn:hover{transform:scale(1.08);border-color:#fff;box-shadow:0 0 25px rgba(255,255,255,0.15)}
.color-btn:active{transform:scale(0.95)}
.color-btn .emoji{font-size:28px}
.round-info{font-size:18px;color:#ffaa33;margin:10px 0}
.feedback{font-size:22px;min-height:36px;margin:12px 0;font-weight:700}
.timer-bar{height:6px;background:rgba(255,255,255,0.08);border-radius:3px;margin:16px auto;max-width:400px;overflow:hidden}
.timer-bar-fill{height:100%;background:linear-gradient(90deg,#ff8800,#ffcc33);border-radius:3px;transition:width .1s linear;width:100%}
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
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
                    <a href="pacman.php">рџ‘ѕ РџР°РєРјР°РЅ</a></div></div><a href="donate.php" class="btn btn-sm">💰 Магазин</a><a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>🎨 Цветовая реакция</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area">
<div>
<div class="round-info">Раунд <span id="roundDisplay">0</span>/20</div>
<div class="color-word" id="colorWordDisplay">Нажми старт</div>
<div class="timer-bar"><div class="timer-bar-fill" id="timerBarFill"></div></div>
<div class="feedback" id="feedbackDisplay"></div>
<div class="color-btns" id="colorBtns"></div>
</div>
</div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const COLORS = [
  { name: 'КРАСНЫЙ', value: '#ff2222', emoji: '🔴' },
  { name: 'СИНИЙ', value: '#2288ff', emoji: '🔵' },
  { name: 'ЗЕЛЁНЫЙ', value: '#22cc44', emoji: '🟢' },
  { name: 'ЖЁЛТЫЙ', value: '#ffdd22', emoji: '🟡' },
  { name: 'ФИОЛЕТОВЫЙ', value: '#bb44ff', emoji: '🟣' },
];
const TOTAL_ROUNDS = 20;
const ROUND_TIME = 3000;
let round = 0;
let score = 0;
let correctAnswers = 0;
let wrongAnswers = 0;
let gameActive = false;
let currentWordColor = null;
let timeoutId = null;
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const roundDisplay = document.getElementById('roundDisplay');
const colorWordDisplay = document.getElementById('colorWordDisplay');
const feedbackDisplay = document.getElementById('feedbackDisplay');
const colorBtns = document.getElementById('colorBtns');
const timerBarFill = document.getElementById('timerBarFill');

function renderColorButtons() {
  colorBtns.innerHTML = '';
  for (const c of COLORS) {
    const btn = document.createElement('div');
    btn.className = 'color-btn';
    btn.style.background = c.value;
    btn.innerHTML = '<span class="emoji">' + c.emoji + '</span>' + c.name;
    btn.dataset.color = c.value;
    btn.addEventListener('click', () => handleClick(c.value));
    colorBtns.appendChild(btn);
  }
}

function pickRandomColor(exclude) {
  let idx;
  do {
    idx = Math.floor(Math.random() * COLORS.length);
  } while (COLORS[idx].value === exclude);
  return COLORS[idx];
}

function pickRandomWord(excludeColor) {
  let idx;
  do {
    idx = Math.floor(Math.random() * COLORS.length);
  } while (COLORS[idx].value === excludeColor);
  return COLORS[idx];
}

function resetGame() {
  clearTimeout(timeoutId);
  round = 0;
  score = 0;
  correctAnswers = 0;
  wrongAnswers = 0;
  gameActive = true;
  scoreDisplay.textContent = '0';
  feedbackDisplay.textContent = '';
  timerBarFill.style.width = '100%';
  renderColorButtons();
  nextRound();
}

function nextRound() {
  if (!gameActive || round >= TOTAL_ROUNDS) {
    endGame();
    return;
  }
  round++;
  roundDisplay.textContent = round;
  feedbackDisplay.textContent = '';
  timerBarFill.style.width = '100%';
  const textColor = pickRandomColor(null);
  const wordColor = pickRandomColor(textColor.value);
  currentWordColor = textColor.value;
  colorWordDisplay.textContent = wordColor.name;
  colorWordDisplay.style.color = textColor.value;
  let startTime = Date.now();
  function tick() {
    const elapsed = Date.now() - startTime;
    const pct = Math.max(0, 100 - (elapsed / ROUND_TIME) * 100);
    timerBarFill.style.width = pct + '%';
    if (pct <= 0) {
      feedbackDisplay.textContent = '⏰ Время вышло!';
      feedbackDisplay.style.color = '#ff4455';
      setTimeout(nextRound, 500);
    } else {
      timeoutId = setTimeout(tick, 50);
    }
  }
  timeoutId = setTimeout(tick, 50);
}

function handleClick(color) {
  if (!gameActive) return;
  clearTimeout(timeoutId);
  if (color === currentWordColor) {
    score += 50;
    correctAnswers++;
    feedbackDisplay.textContent = '✅ +50';
    feedbackDisplay.style.color = '#44dd66';
  } else {
    score -= 20;
    wrongAnswers++;
    feedbackDisplay.textContent = '❌ -20';
    feedbackDisplay.style.color = '#ff4455';
  }
  if (score < 0) score = 0;
  scoreDisplay.textContent = score;
  setTimeout(nextRound, 400);
}

function endGame() {
  gameActive = false;
  colorWordDisplay.textContent = 'Игра окончена!';
  colorWordDisplay.style.color = '#ffaa33';
  const formData = new FormData();
  formData.append('score', score);
  fetch('color_match.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
    .catch(() => {});
}

renderColorButtons();
</script></body></html>
