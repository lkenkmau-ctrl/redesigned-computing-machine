<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'sudoku', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.sudoku", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.sudoku", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Судоку — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.sudoku-grid { display: grid; grid-template-columns: repeat(9, 44px); gap: 1px; background: rgba(255,136,0,0.15); padding: 2px; border-radius: 8px; margin: 0 auto; width: fit-content; border: 2px solid #ff8800; }
.sudoku-grid input { width: 44px; height: 44px; text-align: center; font-size: 20px; font-weight: 700; padding: 0; margin: 0; border-radius: 0; background: rgba(30,16,4,0.9); color: #e8d5b0; border: 1px solid rgba(255,136,0,0.1); }
.sudoku-grid input:focus { outline: none; box-shadow: inset 0 0 8px rgba(255,136,0,0.3); z-index: 1; position: relative; }
.sudoku-grid input:disabled { color: #ffcc66; background: rgba(40,22,5,0.9); font-weight: 700; }
.sudoku-grid input.correct { background: rgba(0,180,0,0.25) !important; }
.sudoku-grid input.wrong { background: rgba(255,50,50,0.25) !important; }
.sudoku-grid .b-r { border-right: 2px solid #ff8800 !important; }
.sudoku-grid .b-b { border-bottom: 2px solid #ff8800 !important; }
.sudoku-msg { font-size: 16px; font-weight: 600; margin: 10px 0; min-height: 30px; }
#mistakesDisplay { color: #ff5555; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link"><?= $site_name ?></a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
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
<h1>🧩 Судоку</h1>
<div class="game-info-bar">
<div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div>
<div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
<div class="game-info-item"><span class="lbl">Ошибки</span><span class="val" id="mistakesDisplay">0</span></div>
</div>
<div class="game-area" id="gameArea"></div>
<div class="sudoku-msg" id="statusMsg"></div>
<div class="game-controls">
<button class="btn" onclick="checkSolution()">✅ Проверить</button>
<button class="btn" onclick="newPuzzle()">🔄 Новая игра</button>
</div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const puzzles = [
    { givens: [
        [5,3,0,0,7,0,0,0,0],
        [6,0,0,1,9,5,0,0,0],
        [0,9,8,0,0,0,0,6,0],
        [8,0,0,0,6,0,0,0,3],
        [4,0,0,8,0,3,0,0,1],
        [7,0,0,0,2,0,0,0,6],
        [0,6,0,0,0,0,2,8,0],
        [0,0,0,4,1,9,0,0,5],
        [0,0,0,0,8,0,0,7,9]
    ], solution: [
        [5,3,4,6,7,8,9,1,2],
        [6,7,2,1,9,5,3,4,8],
        [1,9,8,3,4,2,5,6,7],
        [8,5,9,7,6,1,4,2,3],
        [4,2,6,8,5,3,7,9,1],
        [7,1,3,9,2,4,8,5,6],
        [9,6,1,5,3,7,2,8,4],
        [2,8,7,4,1,9,6,3,5],
        [3,4,5,2,8,6,1,7,9]
    ]},
    { givens: [
        [0,0,0,2,6,0,7,0,1],
        [6,8,0,0,7,0,0,9,0],
        [1,9,0,0,0,4,5,0,0],
        [8,2,0,1,0,0,0,4,0],
        [0,0,4,6,0,2,9,0,0],
        [0,5,0,0,0,3,0,2,8],
        [0,0,9,3,0,0,0,7,4],
        [0,4,0,0,5,0,0,3,6],
        [7,0,3,0,1,8,0,0,0]
    ], solution: [
        [4,3,5,2,6,9,7,8,1],
        [6,8,2,5,7,1,4,9,3],
        [1,9,7,8,3,4,5,6,2],
        [8,2,6,1,9,5,3,4,7],
        [3,7,4,6,8,2,9,1,5],
        [9,5,1,7,4,3,6,2,8],
        [5,1,9,3,2,6,8,7,4],
        [2,4,8,9,5,7,1,3,6],
        [7,6,3,4,1,8,2,5,9]
    ]}
];

let currentPuzzle = 0;
let mistakes = 0;
let completed = false;

function newPuzzle() {
    mistakes = 0;
    completed = false;
    currentPuzzle = (currentPuzzle + 1) % puzzles.length;
    document.getElementById('scoreDisplay').textContent = '0';
    document.getElementById('mistakesDisplay').textContent = '0';
    document.getElementById('statusMsg').textContent = 'Заполните пустые клетки цифрами от 1 до 9';
    renderGrid();
}

function renderGrid() {
    const area = document.getElementById('gameArea');
    area.innerHTML = '';
    const grid = document.createElement('div');
    grid.className = 'sudoku-grid';

    const p = puzzles[currentPuzzle];
    for (let r = 0; r < 9; r++) {
        for (let c = 0; c < 9; c++) {
            const input = document.createElement('input');
            input.type = 'text';
            input.maxLength = 1;
            input.dataset.r = r;
            input.dataset.c = c;
            if (r % 3 === 2 && r < 8) input.classList.add('b-b');
            if (c % 3 === 2 && c < 8) input.classList.add('b-r');

            if (p.givens[r][c] !== 0) {
                input.value = p.givens[r][c];
                input.disabled = true;
            } else {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^1-9]/g, '').slice(0, 1);
                });
            }
            grid.appendChild(input);
        }
    }
    area.appendChild(grid);
}

function checkSolution() {
    if (completed) { document.getElementById('statusMsg').textContent = '✅ Уже решено! Начните новую игру.'; return; }
    const inputs = document.querySelectorAll('.sudoku-grid input');
    const p = puzzles[currentPuzzle];
    let allFilled = true;
    let newMistakes = 0;

    inputs.forEach(input => {
        const r = parseInt(input.dataset.r);
        const c = parseInt(input.dataset.c);
        if (input.disabled) return;
        const val = input.value.trim();
        if (!val) { allFilled = false; return; }
        const num = parseInt(val);
        if (num !== p.solution[r][c]) {
            input.classList.add('wrong');
            input.classList.remove('correct');
            newMistakes++;
        } else {
            input.classList.add('correct');
            input.classList.remove('wrong');
        }
    });

    if (!allFilled) {
        document.getElementById('statusMsg').textContent = '⚠️ Заполните все пустые клетки';
        return;
    }

    mistakes += newMistakes;
    document.getElementById('mistakesDisplay').textContent = mistakes;

    let score = Math.max(0, 500 - mistakes * 50);
    document.getElementById('scoreDisplay').textContent = score;

    let hasWrong = false;
    inputs.forEach(input => {
        if (input.classList.contains('wrong')) hasWrong = true;
    });

    if (!hasWrong) {
        completed = true;
        document.getElementById('statusMsg').textContent = '🎉 Правильно! Счёт: ' + score;
        const formData = new FormData();
        formData.append('score', score);
        fetch('sudoku.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
            .catch(() => {});
    } else {
        document.getElementById('statusMsg').textContent = '❌ ' + newMistakes + ' ошибок. Штраф -' + (newMistakes * 50) + ' очков';
    }
}

newPuzzle();
</script></body></html>
