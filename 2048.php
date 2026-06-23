<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>2048</title>
<link rel="stylesheet" href="style.css">
<style>
.game-wrapper-2048 { max-width: 500px; margin: 0 auto; text-align: center; }
.board {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    background: #1a1a2e;
    border: 2px solid rgba(0,170,0,0.3);
    border-radius: 12px;
    padding: 8px;
    margin: 16px auto;
    width: min(85vw, 420px);
    aspect-ratio: 1 / 1;
    position: relative;
}
.tile {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: 800;
    font-size: clamp(18px, 5vw, 32px);
    transition: all 0.1s ease;
    user-select: none;
    aspect-ratio: 1 / 1;
    background: rgba(255,255,255,0.05);
}
.tile.tile-0 { background: rgba(255,255,255,0.03); }
.tile.tile-2 { background: #eee4da; color: #776e65; }
.tile.tile-4 { background: #ede0c8; color: #776e65; }
.tile.tile-8 { background: #f2b179; color: #f9f6f2; }
.tile.tile-16 { background: #f59563; color: #f9f6f2; }
.tile.tile-32 { background: #f67c5f; color: #f9f6f2; }
.tile.tile-64 { background: #f65e3b; color: #f9f6f2; }
.tile.tile-128 { background: #edcf72; color: #f9f6f2; font-size: clamp(14px, 4vw, 26px); }
.tile.tile-256 { background: #edcc61; color: #f9f6f2; font-size: clamp(14px, 4vw, 26px); }
.tile.tile-512 { background: #edc850; color: #f9f6f2; font-size: clamp(14px, 4vw, 26px); }
.tile.tile-1024 { background: #edc53f; color: #f9f6f2; font-size: clamp(12px, 3vw, 20px); }
.tile.tile-2048 { background: #edc22e; color: #f9f6f2; font-size: clamp(12px, 3vw, 20px); }
.tile.tile-super { background: #3c3a32; color: #f9f6f2; font-size: clamp(11px, 2.5vw, 16px); }
.tile.tile-new { animation: tilePop 0.2s ease; }
.tile.tile-merge { animation: tileMerge 0.2s ease; }
@keyframes tilePop {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
@keyframes tileMerge {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}
.game-overlay {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0.65);
    border-radius: 12px;
    z-index: 10;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.4s ease;
}
.game-overlay.show { opacity: 1; pointer-events: auto; }
.game-overlay .msg {
    background: rgba(22,33,62,0.95);
    border: 1px solid rgba(0,170,0,0.3);
    border-radius: 16px;
    padding: 28px 36px;
    text-align: center;
}
.game-overlay .msg h2 { font-size: 28px; margin: 0 0 8px; }
.game-overlay .msg h2::after { display: none; }
.game-overlay .msg p { color: #aaa; margin: 4px 0; font-size: 14px; }
.key-hint { display: flex; gap: 4px; justify-content: center; margin: 12px 0; flex-wrap: wrap; }
.key { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; padding: 6px 14px; font-size: 13px; color: #888; font-family: monospace; }
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
                    <a href="snake.php">?? Змейка</a>
                    <a href="tetris.php">?? Тетрис</a>
                    <a href="2048.php">?? 2048</a>
                    <a href="tictactoe.php">? Крестики-нолики</a>
                    <a href="guess.php">? Угадай число</a>
                    <a href="memory.php">?? Память</a>
                    <a href="clicker.php">?? Кликер</a>
                    <a href="quiz.php">?? Викторина</a>
                    <a href="flappy.php">?? Flappy Bird</a>
                    <a href="reaction.php">? Reaction Test</a>
                    <a href="minesweeper.php">?? Сапёр</a>
                    <a href="hangman.php">?? Виселица</a>
                    <a href="simon.php">?? Саймон</a>
                    <a href="pong.php">?? Понг</a>
                    <a href="invaders.php">?? Инвейдеры</a>
                    <a href="breakout.php">?? Арканоид</a>
                    <a href="sudoku.php">?? Судоку</a>
                    <a href="wordle.php">?? Вордли</a>
                    <a href="dino.php">?? Динозаврик</a>
                    <a href="rps.php">? Камень-Ножницы</a>
                    <a href="typing.php">?? Печать</a>
                    <a href="color_match.php">?? Цвет</a>
                    <a href="balloon.php">?? Шарики</a>
                    <a href="whack.php">?? Крот</a>
                    <a href="hanoi.php">?? Ханой</a>
                    <a href="connect4.php">?? 4 в ряд</a>
                    <a href="math.php">?? Математика</a>
                    <a href="fifteen.php">?? Пятнашки</a>
                    <a href="asteroids.php">?? Астероиды</a>
                    <a href="pacman.php">?? Пакман</a>
                </div>
            </div>
            <a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a>
            <a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper-2048 animate-in">
        <h1>2048</h1>
        <p style="color:#888;margin-bottom:16px;">РЎРѕРµРґРёРЅСЏР№ РїР»РёС‚РєРё Рё РЅР°Р±РµСЂРё 2048!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">РћС‡РєРё</span><span class="val" id="scoreDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Р›СѓС‡С€Р°СЏ РїР»РёС‚РєР°</span><span class="val" id="bestTileDisplay">0</span></div>
        </div>

        <div class="board" id="board">
            <div class="game-overlay" id="gameOverlay">
                <div class="msg">
                    <h2 id="overlayTitle">рџЋ‰ РџРѕР±РµРґР°!</h2>
                    <p id="overlayScore">РћС‡РєРё: 0</p>
                    <button id="overlayBtn" class="btn" style="margin-top:12px;">рџ”„ РќРѕРІР°СЏ РёРіСЂР°</button>
                </div>
            </div>
        </div>

        <div class="key-hint">
            <span class="key">в¬†</span>
            <span class="key">в¬‡</span>
            <span class="key">в¬…</span>
            <span class="key">вћЎ</span>
        </div>

        <div class="game-controls">
            <button id="newGameBtn" class="btn" style="min-width:140px;">рџ”„ РќРѕРІР°СЏ РёРіСЂР°</button>
            <a href="profile.php" class="btn btn-outline">РџСЂРѕС„РёР»СЊ</a>
        </div>

        <div id="result" style="font-size:16px;font-weight:600;min-height:24px;"></div>

        <div style="margin-top:16px;background:rgba(22,33,62,0.5);border-radius:10px;padding:16px;text-align:left;font-size:13px;color:#888;">
            <strong style="color:#aaa;">РџСЂР°РІРёР»Р°:</strong> РСЃРїРѕР»СЊР·СѓР№ СЃС‚СЂРµР»РєРё, С‡С‚РѕР±С‹ РґРІРёРіР°С‚СЊ РїР»РёС‚РєРё. РћРґРёРЅР°РєРѕРІС‹Рµ РїР»РёС‚РєРё СЃР»РёРІР°СЋС‚СЃСЏ РІ РѕРґРЅСѓ. РќР°Р±РµСЂРё <strong style="color:#00ff00;">2048</strong>, С‡С‚РѕР±С‹ РІС‹РёРіСЂР°С‚СЊ!
        </div>
    </div>
</div>

<script>
const boardEl = document.getElementById('board');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestTileDisplay = document.getElementById('bestTileDisplay');
const newGameBtn = document.getElementById('newGameBtn');
const resultDiv = document.getElementById('result');
const overlay = document.getElementById('gameOverlay');
const overlayTitle = document.getElementById('overlayTitle');
const overlayScore = document.getElementById('overlayScore');
const overlayBtn = document.getElementById('overlayBtn');

let grid = [];
let score = 0;
let bestTile = 0;
let gameOver = false;
let won = false;
let saved = false;

function init() {
    grid = Array.from({length: 4}, () => Array(4).fill(0));
    score = 0;
    bestTile = 0;
    gameOver = false;
    won = false;
    saved = false;
    overlay.classList.remove('show');
    resultDiv.innerHTML = '';
    addRandomTile();
    addRandomTile();
    render();
}

function addRandomTile() {
    let empty = [];
    for (let r = 0; r < 4; r++) {
        for (let c = 0; c < 4; c++) {
            if (grid[r][c] === 0) empty.push({r, c});
        }
    }
    if (empty.length === 0) return;
    let {r, c} = empty[Math.floor(Math.random() * empty.length)];
    grid[r][c] = Math.random() < 0.9 ? 2 : 4;
}

function render(mergedCells, newCell) {
    boardEl.querySelectorAll('.tile').forEach(el => el.remove());
    for (let r = 0; r < 4; r++) {
        for (let c = 0; c < 4; c++) {
            let div = document.createElement('div');
            div.className = 'tile';
            let val = grid[r][c];
            if (val === 0) {
                div.classList.add('tile-0');
            } else {
                let cls = val <= 2048 ? 'tile-' + val : 'tile-super';
                div.classList.add(cls);
                div.textContent = val;
            }
            if (newCell && newCell.r === r && newCell.c === c) {
                div.classList.add('tile-new');
            }
            if (mergedCells) {
                for (let m of mergedCells) {
                    if (m.r === r && m.c === c) {
                        div.classList.add('tile-merge');
                    }
                }
            }
            boardEl.appendChild(div);
        }
    }
    scoreDisplay.textContent = score;
    bestTileDisplay.textContent = bestTile;
}

function slideRow(row) {
    let arr = row.filter(v => v !== 0);
    let merged = [];
    let newRow = [];
    let i = 0;
    while (i < arr.length) {
        if (i + 1 < arr.length && arr[i] === arr[i + 1]) {
            let val = arr[i] * 2;
            newRow.push(val);
            score += val;
            if (val > bestTile) bestTile = val;
            merged.push(newRow.length - 1);
            i += 2;
        } else {
            newRow.push(arr[i]);
            i++;
        }
    }
    while (newRow.length < 4) newRow.push(0);
    return {row: newRow, merged};
}

function slideLeft() {
    let moved = false;
    let allMerged = [];
    for (let r = 0; r < 4; r++) {
        let result = slideRow(grid[r]);
        let oldRow = [...grid[r]];
        grid[r] = result.row;
        if (oldRow.join(',') !== grid[r].join(',')) moved = true;
        for (let idx of result.merged) {
            allMerged.push({r, c: idx});
        }
    }
    return {moved, merged: allMerged};
}

function slideRight() {
    let moved = false;
    let allMerged = [];
    for (let r = 0; r < 4; r++) {
        let reversed = [...grid[r]].reverse();
        let result = slideRow(reversed);
        result.row.reverse();
        let mergedIdx = result.merged.map(i => 3 - i);
        let oldRow = [...grid[r]];
        grid[r] = result.row;
        if (oldRow.join(',') !== grid[r].join(',')) moved = true;
        for (let idx of mergedIdx) {
            allMerged.push({r, c: idx});
        }
    }
    return {moved, merged: allMerged};
}

function slideUp() {
    let moved = false;
    let allMerged = [];
    for (let c = 0; c < 4; c++) {
        let col = [grid[0][c], grid[1][c], grid[2][c], grid[3][c]];
        let result = slideRow(col);
        let oldCol = [...col];
        for (let r = 0; r < 4; r++) grid[r][c] = result.row[r];
        if (oldCol.join(',') !== result.row.join(',')) moved = true;
        for (let idx of result.merged) {
            allMerged.push({r: idx, c});
        }
    }
    return {moved, merged: allMerged};
}

function slideDown() {
    let moved = false;
    let allMerged = [];
    for (let c = 0; c < 4; c++) {
        let col = [grid[3][c], grid[2][c], grid[1][c], grid[0][c]];
        let result = slideRow(col);
        let oldCol = [...col];
        for (let r = 0; r < 4; r++) grid[3 - r][c] = result.row[r];
        if (oldCol.join(',') !== result.row.join(',')) moved = true;
        for (let idx of result.merged) {
            allMerged.push({r: 3 - idx, c});
        }
    }
    return {moved, merged: allMerged};
}

function canMove() {
    for (let r = 0; r < 4; r++) {
        for (let c = 0; c < 4; c++) {
            if (grid[r][c] === 0) return true;
            if (c < 3 && grid[r][c] === grid[r][c + 1]) return true;
            if (r < 3 && grid[r][c] === grid[r + 1][c]) return true;
        }
    }
    return false;
}

function hasWon() {
    for (let r = 0; r < 4; r++) {
        for (let c = 0; c < 4; c++) {
            if (grid[r][c] === 2048) return true;
        }
    }
    return false;
}

function move(direction) {
    if (gameOver) return;
    let result;
    switch (direction) {
        case 'left': result = slideLeft(); break;
        case 'right': result = slideRight(); break;
        case 'up': result = slideUp(); break;
        case 'down': result = slideDown(); break;
    }
    if (result.moved) {
        let newTile = null;
        let empty = [];
        for (let r = 0; r < 4; r++) {
            for (let c = 0; c < 4; c++) {
                if (grid[r][c] === 0) empty.push({r, c});
            }
        }
        if (empty.length > 0) {
            let pos = empty[Math.floor(Math.random() * empty.length)];
            grid[pos.r][pos.c] = Math.random() < 0.9 ? 2 : 4;
            newTile = pos;
        }
        render(result.merged, newTile);

        if (!won && hasWon()) {
            won = true;
            overlayTitle.textContent = 'рџЋ‰ РџРѕР±РµРґР°!';
            overlayScore.textContent = 'РћС‡РєРё: ' + score;
            overlay.classList.add('show');
        }

        if (!canMove()) {
            gameOver = true;
            if (!saved) {
                saved = true;
                fetch('api.php?action=save_score&game=2048&level=1&points=' + score)
                    .then(r => r.text())
                    .then(t => { resultDiv.innerHTML = 'рџЋ® РРіСЂР° РѕРєРѕРЅС‡РµРЅР°! +<strong style="color:#ffd700;">' + score + '</strong> РѕС‡РєРѕРІ Р·Р°С‡РёСЃР»РµРЅРѕ'; })
                    .catch(() => { resultDiv.innerHTML = 'вљ пёЏ РРіСЂР° РѕРєРѕРЅС‡РµРЅР°! РћС€РёР±РєР° СЃРѕС…СЂР°РЅРµРЅРёСЏ.'; });
            }
            overlayTitle.textContent = 'рџµ РРіСЂР° РѕРєРѕРЅС‡РµРЅР°';
            overlayScore.textContent = 'РћС‡РєРё: ' + score;
            overlay.classList.add('show');
        }
    }
}

document.addEventListener('keydown', e => {
    if (gameOver) return;
    switch (e.key) {
        case 'ArrowLeft': e.preventDefault(); move('left'); break;
        case 'ArrowRight': e.preventDefault(); move('right'); break;
        case 'ArrowUp': e.preventDefault(); move('up'); break;
        case 'ArrowDown': e.preventDefault(); move('down'); break;
    }
});

newGameBtn.addEventListener('click', () => {
    init();
});

overlayBtn.addEventListener('click', () => {
    init();
});

init();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
