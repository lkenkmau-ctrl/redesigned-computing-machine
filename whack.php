<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'whack', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.whack", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.whack", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>РЈРґР°СЂСЊ РєСЂРѕС‚Р° вЂ” DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.whack-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;max-width:400px;margin:20px auto}
.hole{position:relative;aspect-ratio:1;background:radial-gradient(ellipse at center,#2d1b00 0%,#1a0a00 70%,transparent 100%);border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:48px;transition:all .1s;overflow:hidden;border:2px solid rgba(255,136,0,0.1)}
.hole-inner{position:absolute;bottom:-20%;left:50%;transform:translateX(-50%);width:120%;height:60%;background:radial-gradient(ellipse at center,#1a0a00,#0d0500);border-radius:50%}
.mole{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:52px;animation:popUp .15s ease-out;cursor:pointer;z-index:2;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.5));transition:transform .1s}
.mole:active{transform:translate(-50%,-50%) scale(0.85)}
.bomb{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);font-size:44px;animation:popUp .15s ease-out;cursor:pointer;z-index:2;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.5))}
@keyframes popUp{0%{transform:translate(-50%,50%) scale(0)}100%{transform:translate(-50%,-50%) scale(1)}}
.whack-hud{display:flex;gap:16px;justify-content:center;margin:10px 0;font-size:15px;flex-wrap:wrap}
.whack-hud span{background:rgba(40,22,5,0.6);padding:6px 14px;border-radius:8px;border:1px solid rgba(255,136,0,0.1)}
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">рџЋ® РРіСЂС‹ в–ѕ</button><div class="dropdown-content">
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
<a href="pacman.php">👾 Пакман</a>
</div></div><a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a><a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>рџ”Ё РЈРґР°СЂСЊ РєСЂРѕС‚Р°</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">РЎС‡С‘С‚</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Р РµРєРѕСЂРґ</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area">
<div>
<div class="whack-hud">
<span>вЏ± <span id="timerDisplay">30</span>СЃ</span>
<span>рџ”Ё <span id="hitsDisplay">0</span></span>
</div>
<div class="whack-grid" id="whackGrid"></div>
</div>
</div>
<div class="game-controls"><button class="btn" onclick="resetGame()">рџ”„ РќРѕРІР°СЏ РёРіСЂР°</button></div>
</div></div>
<footer><p>DonateCraft вЂ” Р·Р°СЂР°Р±Р°С‚С‹РІР°Р№ РґРѕРЅР°С‚РЅС‹Рµ РїРѕРёРЅС‚С‹ Р·Р° РјРёРЅРё-РёРіСЂС‹</p></footer>
<script>
const GAME_TIME = 30;
const GRID_SIZE = 3;
const TOTAL_HOLES = 9;
const MOLE_LIFETIME = 1200;
const BOMB_CHANCE = 0.18;
const SPAWN_INTERVAL = 700;
let holes = [];
let score = 0;
let timeLeft = GAME_TIME;
let gameActive = false;
let timer = null;
let spawnTimer = null;
let hits = 0;
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const timerDisplay = document.getElementById('timerDisplay');
const hitsDisplay = document.getElementById('hitsDisplay');
const grid = document.getElementById('whackGrid');

function initGrid() {
  grid.innerHTML = '';
  holes = [];
  for (let i = 0; i < TOTAL_HOLES; i++) {
    const hole = document.createElement('div');
    hole.className = 'hole';
    hole.dataset.index = i;
    hole.innerHTML = '<div class="hole-inner"></div>';
    hole.addEventListener('click', () => whack(i));
    grid.appendChild(hole);
    holes.push({ el: hole, creature: null, occupied: false });
  }
}

function resetGame() {
  clearInterval(timer);
  clearInterval(spawnTimer);
  score = 0;
  timeLeft = GAME_TIME;
  hits = 0;
  gameActive = true;
  scoreDisplay.textContent = '0';
  timerDisplay.textContent = GAME_TIME;
  hitsDisplay.textContent = '0';
  for (const h of holes) {
    if (h.creature) {
      h.creature.remove();
      h.creature = null;
    }
    h.occupied = false;
  }
  timer = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = timeLeft;
    if (timeLeft <= 0) endGame();
  }, 1000);
  spawnTimer = setInterval(spawnCreatures, SPAWN_INTERVAL);
  spawnCreatures();
}

function spawnCreatures() {
  if (!gameActive) return;
  const available = holes.filter(h => !h.occupied);
  if (available.length === 0) return;
  const count = Math.min(2 + Math.floor(Math.random() * 2), available.length);
  const chosen = [];
  const pool = [...available];
  for (let i = 0; i < count; i++) {
    const idx = Math.floor(Math.random() * pool.length);
    chosen.push(pool[idx]);
    pool.splice(idx, 1);
  }
  for (const h of chosen) {
    const isBomb = Math.random() < BOMB_CHANCE;
    const el = document.createElement('div');
    el.className = isBomb ? 'bomb' : 'mole';
    el.textContent = isBomb ? 'рџ’Ј' : 'рџђ№';
    h.el.appendChild(el);
    h.creature = el;
    h.occupied = true;
    h.isBomb = isBomb;
    setTimeout(() => {
      if (h.creature === el) {
        el.remove();
        h.creature = null;
        h.occupied = false;
      }
    }, MOLE_LIFETIME);
  }
}

function whack(index) {
  if (!gameActive) return;
  const h = holes[index];
  if (!h.occupied || !h.creature) return;
  if (h.isBomb) {
    score -= 20;
    if (score < 0) score = 0;
    h.creature.textContent = 'рџ’Ґ';
    h.creature.style.animation = 'none';
    h.creature.style.transform = 'translate(-50%,-50%) scale(1.5)';
    setTimeout(() => {
      h.creature.remove();
      h.creature = null;
      h.occupied = false;
    }, 200);
  } else {
    score += 10;
    hits++;
    hitsDisplay.textContent = hits;
    h.creature.textContent = 'рџ’«';
    h.creature.style.animation = 'none';
    h.creature.style.transform = 'translate(-50%,-50%) scale(1.5)';
    setTimeout(() => {
      h.creature.remove();
      h.creature = null;
      h.occupied = false;
    }, 200);
  }
  scoreDisplay.textContent = score;
}

function endGame() {
  gameActive = false;
  clearInterval(timer);
  clearInterval(spawnTimer);
  for (const h of holes) {
    if (h.creature) {
      h.creature.remove();
      h.creature = null;
    }
    h.occupied = false;
  }
  const formData = new FormData();
  formData.append('score', score);
  fetch('whack.php', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
    .catch(() => {});
}

initGrid();
</script></body></html>
