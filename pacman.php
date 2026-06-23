<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'pacman', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.pacman", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.pacman", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Пакман — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
#gameCanvas { border: 2px solid rgba(255,136,0,0.25); background: #0a0a15; border-radius: 8px; }
.controls-hint { display: flex; gap: 4px; justify-content: center; margin: 12px 0; flex-wrap: wrap; }
.key { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; padding: 6px 12px; font-size: 12px; color: #888; font-family: monospace; }
.game-status { font-size: 16px; min-height: 24px; margin: 10px 0; color: #ffaa33; }
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
<h1>👾 Пакман</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div><div class="game-info-item"><span class="lbl">Жизни</span><span class="val" id="livesDisplay">3</span></div></div>
<div class="game-status" id="statusDisplay">⬆ ⬇ ⬅ ➡ — собирай точки, избегай призраков!</div>
<div class="game-area"><canvas id="gameCanvas" width="400" height="400"></canvas></div>
<div class="controls-hint">
<span class="key">⬆</span><span class="key">⬇</span><span class="key">⬅</span><span class="key">➡</span>
</div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const livesDisplay = document.getElementById('livesDisplay');
const statusDisplay = document.getElementById('statusDisplay');

const COLS = 20, ROWS = 20, TS = 20;
const W = 400, H = 400;

// 0=wall, 1=dot, 2=empty, 3=ghost house, 4=power pellet
const MAZE_DATA = [
    [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    [0,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,0],
    [0,1,0,0,0,0,1,0,0,1,0,1,0,0,1,0,0,0,1,0],
    [0,4,0,0,0,0,1,0,0,1,0,1,0,0,1,0,0,0,4,0],
    [0,1,0,0,0,0,1,0,0,1,0,1,0,0,1,0,0,0,1,0],
    [0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0],
    [0,1,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0],
    [0,1,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0],
    [0,1,1,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,0],
    [0,0,0,0,0,0,1,0,0,2,2,2,2,0,1,0,0,0,0,0],
    [2,2,2,2,2,0,1,0,0,2,2,2,2,0,1,0,2,2,2,2],
    [0,0,0,0,0,0,1,0,0,2,2,2,2,0,1,0,0,0,0,0],
    [0,1,1,1,1,1,1,0,0,0,0,0,0,0,1,1,1,1,1,0],
    [0,1,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0],
    [0,1,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0],
    [0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,0],
    [0,1,0,0,0,0,1,0,0,1,0,1,0,0,1,0,0,0,1,0],
    [0,4,0,0,0,0,1,0,0,1,0,1,0,0,1,0,0,0,4,0],
    [0,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,0],
    [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
];

const GHOST_COLORS = ['#ff4444', '#ff88cc', '#44ccff', '#ffaa44'];
const GHOST_NAMES = ['Blinky', 'Pinky', 'Inky', 'Clyde'];

let maze, pacman, ghosts, score, lives, gameOver, saved;
let powerTimer, totalDots, eatenDots;

function cloneMaze() { return MAZE_DATA.map(r => [...r]); }

function resetGame() {
    maze = cloneMaze();
    pacman = { x: 9, y: 15, dir: 0, nextDir: 0, speed: 2, radius: 8 };
    ghosts = [
        { x: 9, y: 8, dir: 1, speed: 1.2, color: GHOST_COLORS[0], frightened: false, inHouse: true },
        { x: 10, y: 8, dir: -1, speed: 1.2, color: GHOST_COLORS[1], frightened: false, inHouse: true },
        { x: 8, y: 8, dir: 1, speed: 1.2, color: GHOST_COLORS[2], frightened: false, inHouse: true },
        { x: 11, y: 8, dir: -1, speed: 1.2, color: GHOST_COLORS[3], frightened: false, inHouse: true },
    ];
    score = 0;
    lives = 3;
    gameOver = false;
    saved = false;
    powerTimer = 0;
    eatenDots = 0;
    totalDots = countDots();
    scoreDisplay.textContent = '0';
    livesDisplay.textContent = '3';
    statusDisplay.textContent = '⬆ ⬇ ⬅ ➡ — собирай точки, избегай призраков!';
    window.pacNextDir = 0;
    draw();
}

function countDots() {
    let c = 0;
    for (let r = 0; r < ROWS; r++) for (let col = 0; col < COLS; col++) { if (maze[r][col] === 1 || maze[r][col] === 4) c++; }
    return c;
}

function isWalkable(col, row) {
    if (col < 0 || col >= COLS || row < 0 || row >= ROWS) return false;
    return maze[row][col] !== 0;
}

function getCell(col, row) {
    if (col < 0 || col >= COLS || row < 0 || row >= ROWS) return 0;
    return maze[row][col];
}

function moveEntity(entity, speed) {
    const dirs = [[0,-1],[1,0],[0,1],[-1,0]]; // up, right, down, left
    const d = dirs[entity.dir];
    let nx = entity.x + d[0] * speed * 0.05;
    let ny = entity.y + d[1] * speed * 0.05;

    // Tunnel wrap
    if (nx < -0.5) nx = COLS - 0.5;
    if (nx > COLS - 0.5) nx = -0.5;

    const col = Math.round(nx);
    const row = Math.round(ny);

    if (isWalkable(col, row) || (entity.inHouse && getCell(col, row) === 3)) {
        entity.x = nx;
        entity.y = ny;
    } else {
        // Bounce back
        entity.x = Math.round(entity.x);
        entity.y = Math.round(entity.y);
        return true; // blocked
    }
    return false;
}

function getRandomDir(col, row, exclude) {
    const dirs = [[0,-1],[1,0],[0,1],[-1,0]];
    const opts = [];
    for (let i = 0; i < 4; i++) {
        if (i === exclude) continue;
        const nc = col + dirs[i][0], nr = row + dirs[i][1];
        if (isWalkable(nc, nr)) opts.push(i);
    }
    if (opts.length === 0) return exclude;
    return opts[Math.floor(Math.random() * opts.length)];
}

function moveGhosts() {
    for (const g of ghosts) {
        if (g.inHouse) {
            g.x += (9 - g.x) * 0.02;
            g.y += (7 - g.y) * 0.02;
            if (Math.abs(g.x - 9) < 0.3 && Math.abs(g.y - 7) < 0.3) { g.x = 9; g.y = 7; g.inHouse = false; }
            continue;
        }

        const col = Math.round(g.x);
        const row = Math.round(g.y);
        const dx = g.x - col, dy = g.y - row;

        if (Math.abs(dx) < 0.05 && Math.abs(dy) < 0.05) {
            g.x = col; g.y = row;
            const dirs = [[0,-1],[1,0],[0,1],[-1,0]];
            let options = [];
            for (let i = 0; i < 4; i++) {
                if ((i + 2) % 4 === g.dir) continue;
                const nc = col + dirs[i][0], nr = row + dirs[i][1];
                if (isWalkable(nc, nr)) options.push(i);
            }

            // Chase AI: prefer direction toward pacman
            if (options.length > 1 && !g.frightened) {
                let bestDir = options[0];
                let bestDist = Infinity;
                for (const d of options) {
                    const nc = col + dirs[d][0], nr = row + dirs[d][1];
                    const dist = (nc - pacman.x) ** 2 + (nr - pacman.y) ** 2;
                    if (dist < bestDist) { bestDist = dist; bestDir = d; }
                }
                g.dir = bestDir;
            } else if (options.length > 0) {
                g.dir = options[Math.floor(Math.random() * options.length)];
            } else {
                g.dir = (g.dir + 2) % 4;
            }
        }
        moveEntity(g, g.speed + (g.frightened ? -0.4 : 0));
    }
}

function eatDot(col, row) {
    const cell = maze[row][col];
    if (cell === 1) { score += 10; maze[row][col] = 2; eatenDots++; return true; }
    if (cell === 4) {
        score += 50; maze[row][col] = 2; eatenDots++;
        powerTimer = 300;
        for (const g of ghosts) { g.frightened = true; }
        return true;
    }
    return false;
}

function checkGhostCollision() {
    for (const g of ghosts) {
        if (g.inHouse) continue;
        const dx = pacman.x - g.x, dy = pacman.y - g.y;
        if (dx * dx + dy * dy < 0.8) {
            if (g.frightened) {
                score += 200;
                g.frightened = false;
                g.x = 9; g.y = 8; g.inHouse = true;
                scoreDisplay.textContent = score;
                statusDisplay.textContent = '👻 Призрак съеден! +200';
            } else {
                lives--;
                livesDisplay.textContent = lives;
                if (lives <= 0) { endGame(false); return true; }
                // Respawn pacman
                pacman.x = 9; pacman.y = 15; pacman.dir = 0;
                for (const gh of ghosts) { gh.x = 9; gh.y = 8; gh.inHouse = true; gh.frightened = false; }
                statusDisplay.textContent = '💔 Потеряна жизнь!';
                setTimeout(() => { statusDisplay.textContent = '⬆ ⬇ ⬅ ➡ — собирай точки!'; }, 1000);
                return true;
            }
        }
    }
    return false;
}

function update() {
    if (gameOver) return;

    // Pacman direction
    if (window.pacNextDir !== undefined) {
        const dirs = [[0,-1],[1,0],[0,1],[-1,0]];
        const d = dirs[window.pacNextDir];
        const nc = Math.round(pacman.x) + d[0], nr = Math.round(pacman.y) + d[1];
        if (isWalkable(nc, nr)) pacman.dir = window.pacNextDir;
    }

    const blocked = moveEntity(pacman, pacman.speed);

    const col = Math.round(pacman.x);
    const row = Math.round(pacman.y);
    const dx = pacman.x - col, dy = pacman.y - row;

    if (Math.abs(dx) < 0.1 && Math.abs(dy) < 0.1) {
        pacman.x = col; pacman.y = row;
        if (eatDot(col, row)) {
            scoreDisplay.textContent = score;
            if (eatenDots >= totalDots) { endGame(true); return; }
        }
    }

    if (powerTimer > 0) {
        powerTimer--;
        if (powerTimer === 0) { for (const g of ghosts) g.frightened = false; }
    }

    moveGhosts();
    if (checkGhostCollision()) return;
}

function endGame(won) {
    gameOver = true;
    if (won) {
        statusDisplay.textContent = `🎉 Победа! Все точки собраны! Счёт: ${score}`;
    } else {
        statusDisplay.textContent = `💀 Игра окончена! Счёт: ${score}`;
    }
    if (!saved) {
        saved = true;
        const formData = new FormData();
        formData.append('score', score);
        fetch('pacman.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
            .catch(() => {});
    }
}

function draw() {
    ctx.fillStyle = '#0a0a15'; ctx.fillRect(0, 0, W, H);

    // Maze
    for (let r = 0; r < ROWS; r++) {
        for (let c = 0; c < COLS; c++) {
            const x = c * TS, y = r * TS;
            const cell = maze[r][c];
            if (cell === 0) {
                ctx.fillStyle = '#1a1a30'; ctx.fillRect(x, y, TS, TS);
                ctx.strokeStyle = '#2a2a50'; ctx.lineWidth = 1;
                ctx.strokeRect(x, y, TS, TS);
            } else if (cell === 1) {
                ctx.fillStyle = '#ffcc88';
                ctx.beginPath(); ctx.arc(x + TS/2, y + TS/2, 3, 0, Math.PI * 2); ctx.fill();
            } else if (cell === 4) {
                ctx.fillStyle = '#ff8844';
                ctx.beginPath(); ctx.arc(x + TS/2, y + TS/2, 6, 0, Math.PI * 2); ctx.fill();
                ctx.fillStyle = '#ffcc66';
                ctx.beginPath(); ctx.arc(x + TS/2, y + TS/2, 4, 0, Math.PI * 2); ctx.fill();
            }
        }
    }

    // Ghost house
    for (let r = 7; r <= 9; r++) {
        for (let c = 8; c <= 11; c++) {
            if (maze[r][c] === 3) {
                ctx.fillStyle = 'rgba(255,100,100,0.08)'; ctx.fillRect(c * TS, r * TS, TS, TS);
            }
        }
    }

    // Pacman
    const px = pacman.x * TS + TS/2, py = pacman.y * TS + TS/2;
    ctx.fillStyle = '#ffcc00';
    ctx.shadowColor = '#ffcc00'; ctx.shadowBlur = 8;
    const mouth = 0.2 + 0.15 * Math.sin(Date.now() / 100);
    const angles = [ -Math.PI/2 + mouth, Math.PI/2 - mouth,
                    0 + mouth, Math.PI - mouth,
                    Math.PI/2 + mouth, Math.PI*3/2 - mouth,
                    Math.PI + mouth, -mouth ];
    const startAngle = angles[pacman.dir * 2] || -mouth;
    const endAngle = angles[pacman.dir * 2 + 1] || Math.PI * 2 - mouth;
    ctx.beginPath();
    ctx.arc(px, py, pacman.radius, startAngle, endAngle + 0.1);
    ctx.lineTo(px, py); ctx.closePath(); ctx.fill();
    ctx.shadowBlur = 0;

    // Ghosts
    for (const g of ghosts) {
        const gx = g.x * TS + TS/2, gy = g.y * TS + TS/2;
        ctx.fillStyle = g.frightened ? '#4466cc' : g.color;
        ctx.shadowColor = g.frightened ? '#4466cc' : g.color;
        ctx.shadowBlur = 6;
        ctx.beginPath();
        ctx.arc(gx, gy - 4, 7, Math.PI, 0);
        ctx.lineTo(gx + 7, gy + 6);
        for (let i = 0; i < 3; i++) {
            ctx.quadraticCurveTo(gx + 7 - (i * 5 + 2.5), gy + 2, gx + 7 - ((i + 1) * 5), gy + 6);
        }
        ctx.closePath(); ctx.fill();
        ctx.shadowBlur = 0;
        // Eyes
        if (!g.frightened) {
            ctx.fillStyle = '#fff';
            ctx.beginPath(); ctx.arc(gx - 3, gy - 5, 2.5, 0, Math.PI * 2); ctx.fill();
            ctx.beginPath(); ctx.arc(gx + 3, gy - 5, 2.5, 0, Math.PI * 2); ctx.fill();
            ctx.fillStyle = '#222';
            ctx.beginPath(); ctx.arc(gx - 3, gy - 5, 1.2, 0, Math.PI * 2); ctx.fill();
            ctx.beginPath(); ctx.arc(gx + 3, gy - 5, 1.2, 0, Math.PI * 2); ctx.fill();
        }
    }

    if (gameOver) {
        ctx.fillStyle = 'rgba(0,0,0,0.6)';
        ctx.fillRect(0, 0, W, H);
        ctx.fillStyle = '#ffcc00';
        ctx.font = 'bold 28px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('👾 Игра окончена!', W/2, H/2 - 20);
        ctx.fillStyle = '#ffaa33';
        ctx.font = '18px Inter, sans-serif';
        ctx.fillText('Счёт: ' + score, W/2, H/2 + 20);
    }
}

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

document.addEventListener('keydown', e => {
    if (gameOver) return;
    switch (e.key) {
        case 'ArrowUp': e.preventDefault(); window.pacNextDir = 0; break;
        case 'ArrowRight': e.preventDefault(); window.pacNextDir = 1; break;
        case 'ArrowDown': e.preventDefault(); window.pacNextDir = 2; break;
        case 'ArrowLeft': e.preventDefault(); window.pacNextDir = 3; break;
    }
});

resetGame();
gameLoop();
</script></body></html>
