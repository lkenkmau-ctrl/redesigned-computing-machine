<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'hanoi', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.hanoi", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.hanoi", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Ханойская башня — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.hanoi-container{display:flex;gap:20px;justify-content:center;align-items:flex-end;margin:30px 0;height:260px;padding:0 10px}
.peg-wrapper{display:flex;flex-direction:column;align-items:center;width:140px;cursor:pointer;position:relative}
.peg-wrapper:hover .peg-pole{box-shadow:0 0 15px rgba(255,136,0,0.15)}
.peg-pole{width:8px;height:220px;background:linear-gradient(to bottom,#5a4a30,#3a2a10);border-radius:4px 4px 0 0;position:relative;transition:box-shadow .2s}
.peg-base{width:130px;height:10px;background:linear-gradient(to right,#5a4a30,#8a7040,#5a4a30);border-radius:4px;margin-top:-2px;box-shadow:0 4px 10px rgba(0,0,0,0.3)}
.peg-label{font-size:13px;color:#8a7a5a;margin-top:6px;font-weight:600}
.peg-title{font-size:11px;color:#6b5a40;margin-top:2px}
.disk{position:absolute;bottom:0;left:50%;transform:translateX(-50%);height:22px;border-radius:6px;border:2px solid rgba(255,255,255,0.1);transition:all .3s;z-index:2;cursor:pointer}
.disk:hover{filter:brightness(1.3)}
.disk.selected{box-shadow:0 0 20px rgba(255,200,0,0.6);border-color:#ffcc00;transform:translateX(-50%) translateY(-8px)}
.disk-0{width:40px;background:linear-gradient(90deg,#ff6b35,#ff8844)}
.disk-1{width:60px;background:linear-gradient(90deg,#35b5ff,#44ccff)}
.disk-2{width:80px;background:linear-gradient(90deg,#35ddaa,#44eebb)}
.disk-3{width:100px;background:linear-gradient(90deg,#ffd700,#ffee44)}
.hanoi-info{font-size:15px;color:#ffaa33;margin:10px 0;min-height:24px}
.hanoi-hud{display:flex;gap:20px;justify-content:center;margin:10px 0;flex-wrap:wrap}
.hanoi-hud span{background:rgba(40,22,5,0.6);padding:6px 16px;border-radius:8px;border:1px solid rgba(255,136,0,0.1);font-size:14px}
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
<h1>🗼 Ханойская башня</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area">
<div>
<div class="hanoi-hud">
<span>🎯 Ходы: <span id="movesDisplay">0</span></span>
<span>📏 Мин. ходов: 15</span>
</div>
<div class="hanoi-container" id="hanoiContainer"></div>
<div class="hanoi-info" id="hanoiInfo">Нажми на колышек, чтобы взять верхний диск, затем нажми другой, чтобы поставить</div>
</div>
</div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const NUM_DISKS = 4;
const NUM_PEGS = 3;
const MIN_MOVES = 15;
let pegs = [[], [], []];
let moves = 0;
let selectedPeg = null;
let gameActive = false;
let gameComplete = false;
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const movesDisplay = document.getElementById('movesDisplay');
const hanoiInfo = document.getElementById('hanoiInfo');
const container = document.getElementById('hanoiContainer');

function resetGame() {
  pegs = [[], [], []];
  for (let i = NUM_DISKS - 1; i >= 0; i--) {
    pegs[0].push(i);
  }
  moves = 0;
  selectedPeg = null;
  gameActive = true;
  gameComplete = false;
  movesDisplay.textContent = '0';
  scoreDisplay.textContent = '0';
  hanoiInfo.textContent = 'Нажми на колышек, чтобы взять верхний диск, затем нажми другой, чтобы поставить';
  render();
}

function render() {
  container.innerHTML = '';
  const labels = ['A', 'B', 'C'];
  const titles = ['Начало', 'Помощник', 'Цель'];
  for (let p = 0; p < NUM_PEGS; p++) {
    const wrapper = document.createElement('div');
    wrapper.className = 'peg-wrapper';
    wrapper.dataset.peg = p;
    const pole = document.createElement('div');
    pole.className = 'peg-pole';
    const disks = pegs[p];
    for (let i = 0; i < disks.length; i++) {
      const d = document.createElement('div');
      d.className = 'disk disk-' + disks[i];
      d.style.bottom = (i * 26) + 'px';
      if (selectedPeg !== null && p === selectedPeg && i === disks.length - 1) {
        d.classList.add('selected');
      }
      pole.appendChild(d);
    }
    wrapper.appendChild(pole);
    const base = document.createElement('div');
    base.className = 'peg-base';
    wrapper.appendChild(base);
    const label = document.createElement('div');
    label.className = 'peg-label';
    label.textContent = labels[p];
    wrapper.appendChild(label);
    const title = document.createElement('div');
    title.className = 'peg-title';
    title.textContent = titles[p];
    wrapper.appendChild(title);
    wrapper.addEventListener('click', () => clickPeg(p));
    container.appendChild(wrapper);
  }
  checkWin();
}

function clickPeg(pegIdx) {
  if (!gameActive || gameComplete) return;
  if (selectedPeg === null) {
    if (pegs[pegIdx].length === 0) {
      hanoiInfo.textContent = '⚠️ На этом колышке нет дисков';
      return;
    }
    selectedPeg = pegIdx;
    hanoiInfo.textContent = 'Выбран колышек ' + (pegIdx === 0 ? 'A' : pegIdx === 1 ? 'B' : 'C') + '. Нажмите куда поставить диск.';
    render();
    return;
  }
  if (selectedPeg === pegIdx) {
    selectedPeg = null;
    hanoiInfo.textContent = 'Отменено. Нажмите колышек с диском.';
    render();
    return;
  }
  const srcPeg = pegs[selectedPeg];
  const dstPeg = pegs[pegIdx];
  const topDisk = srcPeg[srcPeg.length - 1];
  if (dstPeg.length > 0 && dstPeg[dstPeg.length - 1] < topDisk) {
    hanoiInfo.textContent = '❌ Нельзя положить больший диск на меньший!';
    selectedPeg = null;
    render();
    return;
  }
  dstPeg.push(srcPeg.pop());
  moves++;
  movesDisplay.textContent = moves;
  selectedPeg = null;
  hanoiInfo.textContent = 'Ход ' + moves + '. Диск перемещён.';
  render();
}

function checkWin() {
  if (pegs[2].length === NUM_DISKS) {
    gameComplete = true;
    gameActive = false;
    const extra = Math.max(0, moves - MIN_MOVES);
    const score = Math.max(0, 500 - extra * 10);
    scoreDisplay.textContent = score;
    hanoiInfo.textContent = '🎉 Победа! Вы решили за ' + moves + ' ходов! Счёт: ' + score;
    const formData = new FormData();
    formData.append('score', score);
    fetch('hanoi.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
      .catch(() => {});
  }
}

resetGame();
</script></body></html>
