<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>РљСЂРµСЃС‚РёРєРё-РќРѕР»РёРєРё</title>
<link rel="stylesheet" href="style.css">
<style>
.board {
  display: grid;
  grid-template-columns: repeat(3, 120px);
  gap: 4px;
  justify-content: center;
  margin: 20px auto;
  background: rgba(22,33,62,0.6);
  border: 1px solid rgba(0,170,0,0.15);
  border-radius: 12px;
  padding: 8px;
  width: fit-content;
}
.cell {
  width: 120px;
  height: 120px;
  background: rgba(10,10,26,0.8);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  font-weight: 800;
  cursor: pointer;
  transition: all 0.2s ease;
  user-select: none;
}
.cell:hover { border-color: rgba(0,170,0,0.3); background: rgba(22,33,62,0.5); }
.cell.x { color: #4488ff; cursor: default; }
.cell.o { color: #ff4444; cursor: default; }
.cell.win { animation: pulse 0.6s ease 3; }
.cell.taken { cursor: default; }
.cell.taken:hover { border-color: rgba(255,255,255,0.06); background: rgba(10,10,26,0.8); }
.game-status { font-size: 18px; font-weight: 600; min-height: 30px; margin: 8px 0; }
.counter-grid { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin: 12px 0; }
.counter-item { background: rgba(22,33,62,0.5); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 8px 20px; text-align: center; min-width: 80px; }
.counter-item .lbl { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
.counter-item .val { font-size: 22px; font-weight: 700; display: block; }
.counter-item .val.win { color: #00ff00; }
.counter-item .val.loss { color: #ff4444; }
.counter-item .val.draw { color: #ffaa00; }
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
        </div>
      </div>
      <a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a>
      <a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a>
    </nav>
  </div>
</header>
<div class="container">
  <div class="game-wrapper animate-in">
    <h1>вќЊ РљСЂРµСЃС‚РёРєРё-РќРѕР»РёРєРё в­•</h1>
    <p style="color:#888;margin-bottom:8px;">РЎС‹РіСЂР°Р№ РїСЂРѕС‚РёРІ Р±РѕС‚Р°! РўС‹ вЂ” <strong style="color:#4488ff;">X</strong>, Р±РѕС‚ вЂ” <strong style="color:#ff4444;">O</strong></p>

    <div class="game-info-bar">
      <div class="game-info-item"><span class="lbl">РЎС‡С‘С‚</span><span class="val" id="scoreDisplay">0</span></div>
      <div class="game-info-item"><span class="lbl">РЎС‹РіСЂР°РЅРѕ</span><span class="val" id="gamesCount">0</span></div>
    </div>

    <div class="board" id="board"></div>

    <div class="game-status" id="status"></div>

    <div class="counter-grid">
      <div class="counter-item"><span class="lbl">РџРѕР±РµРґС‹</span><span class="val win" id="wins">0</span></div>
      <div class="counter-item"><span class="lbl">РџРѕСЂР°Р¶РµРЅРёСЏ</span><span class="val loss" id="losses">0</span></div>
      <div class="counter-item"><span class="lbl">РќРёС‡СЊРё</span><span class="val draw" id="draws">0</span></div>
    </div>

    <div class="game-controls">
      <button id="newGameBtn" class="btn" style="min-width:160px;">рџ”„ РќРѕРІР°СЏ РёРіСЂР°</button>
    </div>
  </div>
</div>

<script>
const boardEl = document.getElementById('board');
const statusEl = document.getElementById('status');
const scoreDisplay = document.getElementById('scoreDisplay');
const gamesCount = document.getElementById('gamesCount');
const winsEl = document.getElementById('wins');
const lossesEl = document.getElementById('losses');
const drawsEl = document.getElementById('draws');

let board, gameOver, saved;
let wins = 0, losses = 0, draws = 0, totalGames = 0, totalPoints = 0;

const WIN_COMBOS = [
  [0,1,2],[3,4,5],[6,7,8],
  [0,3,6],[1,4,7],[2,5,8],
  [0,4,8],[2,4,6]
];

function initBoard() {
  board = Array(9).fill(null);
  gameOver = false;
  saved = false;
  statusEl.textContent = 'РўРІРѕР№ С…РѕРґ!';
  render();
}

function render() {
  boardEl.innerHTML = '';
  for (let i = 0; i < 9; i++) {
    const cell = document.createElement('div');
    cell.className = 'cell';
    if (board[i] === 'X') { cell.classList.add('x', 'taken'); cell.textContent = 'X'; }
    else if (board[i] === 'O') { cell.classList.add('o', 'taken'); cell.textContent = 'O'; }
    else if (!gameOver) { cell.addEventListener('click', () => playerMove(i)); }
    boardEl.appendChild(cell);
  }
}

function highlightWin(combo) {
  const cells = boardEl.children;
  for (let i = 0; i < 9; i++) {
    if (combo.includes(i)) cells[i].classList.add('win');
  }
}

function checkWinner(b) {
  for (const c of WIN_COMBOS) {
    if (b[c[0]] && b[c[0]] === b[c[1]] && b[c[1]] === b[c[2]])
      return { winner: b[c[0]], combo: c };
  }
  return null;
}

function isFull(b) {
  return b.every(c => c !== null);
}

function playerMove(idx) {
  if (gameOver || board[idx] !== null) return;
  board[idx] = 'X';
  render();

  const result = checkWinner(board);
  if (result) {
    endGame(result.winner, result.combo);
    return;
  }
  if (isFull(board)) {
    endGame('draw');
    return;
  }

  statusEl.textContent = 'РҐРѕРґ Р±РѕС‚Р°...';
  setTimeout(botMove, 300);
}

function botMove() {
  if (gameOver) return;
  const idx = getBotMove();
  if (idx === -1) return;
  board[idx] = 'O';
  render();

  const result = checkWinner(board);
  if (result) {
    endGame(result.winner, result.combo);
    return;
  }
  if (isFull(board)) {
    endGame('draw');
    return;
  }

  statusEl.textContent = 'РўРІРѕР№ С…РѕРґ!';
  render();
}

function getBotMove() {
  const b = board;

  // 1. Win if possible
  for (const c of WIN_COMBOS) {
    const vals = c.map(i => b[i]);
    if (vals.filter(v => v === 'O').length === 2 && vals.includes(null)) {
      return c[vals.indexOf(null)];
    }
  }

  // 2. Block player win
  for (const c of WIN_COMBOS) {
    const vals = c.map(i => b[i]);
    if (vals.filter(v => v === 'X').length === 2 && vals.includes(null)) {
      return c[vals.indexOf(null)];
    }
  }

  // 3. Take center
  if (b[4] === null) return 4;

  // 4. Take corners
  const corners = [0, 2, 6, 8];
  const availableCorners = corners.filter(i => b[i] === null);
  if (availableCorners.length > 0) {
    return availableCorners[Math.floor(Math.random() * availableCorners.length)];
  }

  // 5. Take any remaining
  const available = b.map((v, i) => v === null ? i : null).filter(v => v !== null);
  if (available.length > 0) {
    return available[Math.floor(Math.random() * available.length)];
  }

  return -1;
}

function endGame(winner, combo) {
  gameOver = true;
  saved = false;

  if (winner === 'X') {
    wins++;
    totalPoints += 100;
    if (combo) highlightWin(combo);
    statusEl.innerHTML = 'рџЋ‰ РўС‹ РїРѕР±РµРґРёР»! <strong style="color:#4488ff;">+100 РѕС‡РєРѕРІ</strong>';
  } else if (winner === 'O') {
    losses++;
    if (combo) highlightWin(combo);
    statusEl.innerHTML = 'рџ” Р‘РѕС‚ РїРѕР±РµРґРёР»! РџРѕРїСЂРѕР±СѓР№ РµС‰С‘.';
  } else {
    draws++;
    totalPoints += 10;
    statusEl.innerHTML = 'рџ¤ќ РќРёС‡СЊСЏ! <strong style="color:#ffaa00;">+10 РѕС‡РєРѕРІ</strong>';
  }

  totalGames++;
  updateStats();

  if (!saved) {
    saved = true;
    const points = winner === 'X' ? 100 : winner === 'O' ? 0 : 10;
    fetch('api.php?action=save_score&game=tictactoe&level=1&points=' + points)
      .catch(() => {});
  }
}

function updateStats() {
  scoreDisplay.textContent = totalPoints;
  gamesCount.textContent = totalGames;
  winsEl.textContent = wins;
  lossesEl.textContent = losses;
  drawsEl.textContent = draws;
}

document.getElementById('newGameBtn').addEventListener('click', () => {
  initBoard();
});

initBoard();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
