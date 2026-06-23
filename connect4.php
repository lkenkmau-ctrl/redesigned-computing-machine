<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'connect4', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.connect4", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.connect4", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Четыре в ряд — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#connectCanvas { border: 2px solid rgba(255,136,0,0.25); background: #1a0a00; border-radius: 8px; cursor: pointer; }
.game-status { font-size: 18px; font-weight: 600; min-height: 30px; margin: 10px 0; color: #ffaa33; }
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
<h1>🔴 Четыре в ряд</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div></div>
<div class="game-status" id="statusDisplay">Твой ход — выбери столбец</div>
<div class="game-area"><canvas id="connectCanvas" width="490" height="420"></canvas></div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const canvas = document.getElementById('connectCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const statusDisplay = document.getElementById('statusDisplay');

const COLS = 7, ROWS = 6, SIZE = 60, RADIUS = 24;
let board, currentPlayer, gameOver, score, saved;

function initBoard() {
    board = Array.from({length: COLS}, () => Array(ROWS).fill(null));
    currentPlayer = 'red';
    gameOver = false;
    score = 0;
    saved = false;
    statusDisplay.textContent = 'Твой ход — выбери столбец';
    scoreDisplay.textContent = '0';
}

function resetGame() { initBoard(); draw(); }

function dropPiece(col) {
    if (gameOver || currentPlayer !== 'red') return false;
    for (let r = ROWS - 1; r >= 0; r--) {
        if (board[col][r] === null) {
            board[col][r] = 'red';
            if (checkWin(col, r, 'red')) { endGame('win'); return true; }
            if (isFull()) { endGame('draw'); return true; }
            currentPlayer = 'yellow';
            statusDisplay.textContent = 'Ход ИИ...';
            draw();
            setTimeout(aiMove, 400);
            return true;
        }
    }
    return false;
}

function aiMove() {
    if (gameOver) return;
    let valid = [];
    for (let c = 0; c < COLS; c++) { if (board[c][0] === null) valid.push(c); }
    if (valid.length === 0) return;
    const col = valid[Math.floor(Math.random() * valid.length)];
    for (let r = ROWS - 1; r >= 0; r--) {
        if (board[col][r] === null) {
            board[col][r] = 'yellow';
            if (checkWin(col, r, 'yellow')) { draw(); endGame('lose'); return; }
            if (isFull()) { draw(); endGame('draw'); return; }
            currentPlayer = 'red';
            statusDisplay.textContent = 'Твой ход — выбери столбец';
            draw();
            return;
        }
    }
}

function isFull() {
    return board.every(col => col[0] !== null);
}

function checkWin(col, row, player) {
    const directions = [[1,0],[0,1],[1,1],[1,-1]];
    for (const [dx, dy] of directions) {
        let count = 1;
        for (let dir = -1; dir <= 1; dir += 2) {
            let c = col + dx * dir, r = row + dy * dir;
            while (c >= 0 && c < COLS && r >= 0 && r < ROWS && board[c][r] === player) {
                count++; c += dx * dir; r += dy * dir;
            }
        }
        if (count >= 4) return true;
    }
    return false;
}

function endGame(result) {
    gameOver = true;
    if (result === 'win') { score = 200; statusDisplay.textContent = '🎉 Ты выиграл! +200 очков'; }
    else if (result === 'lose') { score = 0; statusDisplay.textContent = '😵 ИИ победил!'; }
    else { score = 50; statusDisplay.textContent = '🤝 Ничья! +50 очков'; }
    scoreDisplay.textContent = score;
    if (!saved) {
        saved = true;
        const formData = new FormData();
        formData.append('score', score);
        fetch('connect4.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
            .catch(() => {});
    }
}

function draw() {
    ctx.fillStyle = '#1a0a00'; ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#2d1b00';
    ctx.fillRect(10, 0, COLS * SIZE, ROWS * SIZE);
    for (let c = 0; c < COLS; c++) {
        for (let r = 0; r < ROWS; r++) {
            const x = 10 + c * SIZE + SIZE / 2, y = r * SIZE + SIZE / 2 + 10;
            ctx.beginPath(); ctx.arc(x, y, RADIUS, 0, Math.PI * 2);
            ctx.fillStyle = '#1a0a00'; ctx.fill();
            ctx.strokeStyle = 'rgba(255,136,0,0.15)'; ctx.lineWidth = 2; ctx.stroke();
            if (board[c][r]) {
                ctx.beginPath(); ctx.arc(x, y, RADIUS - 4, 0, Math.PI * 2);
                ctx.fillStyle = board[c][r] === 'red' ? '#ff3333' : '#ffcc00';
                ctx.fill();
                ctx.shadowColor = board[c][r] === 'red' ? '#ff3333' : '#ffcc00';
                ctx.shadowBlur = 10; ctx.fill(); ctx.shadowBlur = 0;
            }
        }
    }
}

canvas.addEventListener('click', e => {
    if (gameOver || currentPlayer !== 'red') return;
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const col = Math.floor((x - 10) / SIZE);
    if (col >= 0 && col < COLS) dropPiece(col);
});

initBoard(); draw();
</script></body></html>
