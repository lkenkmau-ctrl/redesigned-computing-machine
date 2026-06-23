<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'minesweeper', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.minesweeper", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.minesweeper", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Сапёр — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#minesweeperGrid { display: inline-grid; grid-template-columns: repeat(9, 36px); gap: 2px; margin: 10px auto; user-select: none; }
.cell { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; border-radius: 4px; cursor: pointer; transition: all 0.1s; }
.cell.hidden { background: rgba(255,136,0,0.15); border: 1px solid rgba(255,136,0,0.2); }
.cell.hidden:hover { background: rgba(255,136,0,0.25); }
.cell.revealed { background: rgba(40,22,5,0.5); border: 1px solid rgba(255,136,0,0.08); cursor: default; }
.cell.flagged { background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.25); }
.cell.flagged::after { content: '⚑'; }
.cell.mine { background: rgba(255,0,0,0.15); border: 1px solid rgba(255,0,0,0.2); }
.cell.mine-hit { background: rgba(255,0,0,0.5); border: 1px solid #ff0000; }
.game-message { font-size: 18px; font-weight: 600; min-height: 30px; margin: 10px 0; color: #ffaa33; }
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
<h1>💣 Сапёр</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-area"><div id="minesweeperGrid"></div></div>
<div id="gameMessage" class="game-message"></div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const ROWS = 9, COLS = 9, MINES = 10;
const TOTAL_SAFE = ROWS * COLS - MINES;
let grid = [];
let gameOver = false;
let firstClick = true;
let revealedCount = 0;
let scoreSubmitted = false;
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');

function createGrid() {
    const container = document.getElementById('minesweeperGrid');
    container.innerHTML = '';
    grid = [];
    revealedCount = 0;
    scoreDisplay.textContent = '0';
    for (let r = 0; r < ROWS; r++) {
        grid[r] = [];
        for (let c = 0; c < COLS; c++) {
            const cell = document.createElement('div');
            cell.className = 'cell hidden';
            cell.dataset.r = r;
            cell.dataset.c = c;
            cell.addEventListener('click', () => handleClick(r, c));
            cell.addEventListener('contextmenu', (e) => { e.preventDefault(); handleRightClick(r, c); });
            container.appendChild(cell);
            grid[r][c] = { el: cell, mine: false, revealed: false, flagged: false, adjacent: 0 };
        }
    }
}

function generateMines(safeR, safeC) {
    let placed = 0;
    while (placed < MINES) {
        const r = Math.floor(Math.random() * ROWS);
        const c = Math.floor(Math.random() * COLS);
        if (grid[r][c].mine) continue;
        if (Math.abs(r - safeR) <= 1 && Math.abs(c - safeC) <= 1) continue;
        grid[r][c].mine = true;
        placed++;
    }
    for (let r = 0; r < ROWS; r++) {
        for (let c = 0; c < COLS; c++) {
            if (grid[r][c].mine) continue;
            let count = 0;
            for (let dr = -1; dr <= 1; dr++) {
                for (let dc = -1; dc <= 1; dc++) {
                    const nr = r + dr, nc = c + dc;
                    if (nr >= 0 && nr < ROWS && nc >= 0 && nc < COLS && grid[nr][nc].mine) count++;
                }
            }
            grid[r][c].adjacent = count;
        }
    }
}

const NUM_COLORS = ['', '#4488ff', '#44cc44', '#ff4444', '#8844ff', '#ff8800', '#ff0088', '#0088ff', '#888'];

function reveal(r, c) {
    if (r < 0 || r >= ROWS || c < 0 || c >= COLS) return;
    const cell = grid[r][c];
    if (cell.revealed || cell.flagged) return;
    cell.revealed = true;
    cell.el.className = 'cell revealed';
    revealedCount++;
    if (cell.mine) {
        cell.el.className = 'cell mine-hit';
        cell.el.textContent = '💣';
        gameOver = true;
        revealAllMines();
        endGame(false);
        return;
    }
    if (cell.adjacent > 0) {
        cell.el.textContent = cell.adjacent;
        cell.el.style.color = NUM_COLORS[cell.adjacent];
    } else {
        for (let dr = -1; dr <= 1; dr++)
            for (let dc = -1; dc <= 1; dc++)
                if (dr !== 0 || dc !== 0) reveal(r + dr, c + dc);
    }
}

function revealAllMines() {
    for (let r = 0; r < ROWS; r++)
        for (let c = 0; c < COLS; c++)
            if (grid[r][c].mine && !grid[r][c].revealed) {
                grid[r][c].el.className = 'cell mine';
                grid[r][c].el.textContent = '💣';
            }
}

function handleClick(r, c) {
    if (gameOver) return;
    const cell = grid[r][c];
    if (cell.flagged) return;
    if (firstClick) {
        firstClick = false;
        generateMines(r, c);
    }
    reveal(r, c);
    if (!gameOver && revealedCount === TOTAL_SAFE) endGame(true);
}

function handleRightClick(r, c) {
    if (gameOver || firstClick) return;
    const cell = grid[r][c];
    if (cell.revealed) return;
    cell.flagged = !cell.flagged;
    cell.el.className = cell.flagged ? 'cell flagged' : 'cell hidden';
}

function endGame(won) {
    gameOver = true;
    const finalScore = revealedCount * 10;
    scoreDisplay.textContent = finalScore;
    document.getElementById('gameMessage').textContent = won ? '🎉 Победа! Все мины обезврежены!' : '💥 Бах! Вы наступили на мину!';
    if (scoreSubmitted) return;
    scoreSubmitted = true;
    const formData = new FormData();
    formData.append('score', finalScore);
    fetch('minesweeper.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
        .catch(() => {});
}

function resetGame() {
    gameOver = false;
    firstClick = true;
    scoreSubmitted = false;
    document.getElementById('gameMessage').textContent = '';
    createGrid();
}

createGrid();
</script></body></html>
