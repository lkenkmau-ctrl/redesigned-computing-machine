<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'math', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.math", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.math", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>РњР°С‚РµРјР°С‚РёРєР° вЂ” DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.math-equation { font-size: 48px; font-weight: 800; color: #ffcc33; margin: 20px 0; letter-spacing: 4px; }
.math-input { width: 160px; font-size: 32px; text-align: center; padding: 12px; margin: 10px 0; }
.timer-bar { height: 8px; background: rgba(255,136,0,0.15); border-radius: 4px; margin: 15px 0; overflow: hidden; }
.timer-fill { height: 100%; background: linear-gradient(90deg, #ff8800, #ff3333); border-radius: 4px; transition: width 0.1s linear; }
.progress-text { font-size: 14px; color: #8a7a5a; margin: 10px 0; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link">DonateCraft</a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">рџЋ® РРіСЂС‹ в–ѕ</button><div class="dropdown-content">
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
</div></div><a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a><a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>рџ§® РњР°С‚РµРјР°С‚РёС‡РµСЃРєРёР№ С‚СЂРµРЅР°Р¶С‘СЂ</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">РЎС‡С‘С‚</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Р РµРєРѕСЂРґ</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div><div class="game-info-item"><span class="lbl">РџСЂР°РІРёР»СЊРЅРѕ</span><span class="val" id="correctDisplay">0 / 20</span></div></div>
<div class="progress-text" id="progressText">РџСЂРёРјРµСЂ 1 РёР· 20</div>
<div class="timer-bar"><div class="timer-fill" id="timerFill" style="width:100%"></div></div>
<div class="math-equation" id="equationDisplay">23 + 17</div>
<input class="math-input" id="answerInput" type="number" placeholder="?" autofocus>
<div class="game-controls">
<button class="btn" id="submitBtn">вњ… РћС‚РІРµС‚РёС‚СЊ</button>
<button class="btn" onclick="resetGame()">рџ”„ РќРѕРІР°СЏ РёРіСЂР°</button>
</div>
<div class="game-status" id="statusDisplay" style="font-size:16px;min-height:24px;margin-top:10px;color:#8a7a5a;"></div>
</div></div>
<footer><p>DonateCraft вЂ” Р·Р°СЂР°Р±Р°С‚С‹РІР°Р№ РґРѕРЅР°С‚РЅС‹Рµ РїРѕРёРЅС‚С‹ Р·Р° РјРёРЅРё-РёРіСЂС‹</p></footer>
<script>
const scoreDisplay = document.getElementById('scoreDisplay');
const bestDisplay = document.getElementById('bestDisplay');
const correctDisplay = document.getElementById('correctDisplay');
const progressText = document.getElementById('progressText');
const equationDisplay = document.getElementById('equationDisplay');
const answerInput = document.getElementById('answerInput');
const submitBtn = document.getElementById('submitBtn');
const statusDisplay = document.getElementById('statusDisplay');
const timerFill = document.getElementById('timerFill');

const TOTAL = 20;
let problems, currentIndex, correctCount, score, timerInterval, timeLeft, saved, gameActive;

function rand(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }

function generateProblems() {
    const ops = ['+', '-', '*'];
    const p = [];
    for (let i = 0; i < TOTAL; i++) {
        const op = ops[rand(0, 2)];
        let a, b, ans;
        if (op === '+') { a = rand(1, 20); b = rand(1, 20); ans = a + b; }
        else if (op === '-') { a = rand(1, 20); b = rand(1, a); ans = a - b; }
        else { a = rand(1, 10); b = rand(1, 10); ans = a * b; }
        p.push({ a, b, op, ans });
    }
    return p;
}

function resetGame() {
    clearInterval(timerInterval);
    problems = generateProblems();
    currentIndex = 0;
    correctCount = 0;
    score = 0;
    saved = false;
    gameActive = true;
    scoreDisplay.textContent = '0';
    statusDisplay.textContent = '';
    answerInput.value = '';
    answerInput.disabled = false;
    submitBtn.disabled = false;
    showProblem();
}

function showProblem() {
    if (currentIndex >= TOTAL) { endGame(); return; }
    const p = problems[currentIndex];
    const opSymbol = p.op === '*' ? 'Г—' : p.op;
    equationDisplay.textContent = `${p.a} ${opSymbol} ${p.b}`;
    progressText.textContent = `РџСЂРёРјРµСЂ ${currentIndex + 1} РёР· ${TOTAL}`;
    correctDisplay.textContent = `${correctCount} / ${TOTAL}`;
    answerInput.value = '';
    answerInput.focus();
    timeLeft = 10;
    timerFill.style.width = '100%';
    clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        timeLeft -= 0.1;
        timerFill.style.width = (timeLeft / 10 * 100) + '%';
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            statusDisplay.textContent = 'вЏ° Р’СЂРµРјСЏ РІС‹С€Р»Рѕ! РџСЂР°РІРёР»СЊРЅС‹Р№ РѕС‚РІРµС‚: ' + problems[currentIndex].ans;
            currentIndex++;
            setTimeout(showProblem, 1200);
        }
    }, 100);
}

function submitAnswer() {
    if (!gameActive || currentIndex >= TOTAL) return;
    clearInterval(timerInterval);
    const val = parseInt(answerInput.value);
    const p = problems[currentIndex];
    if (val === p.ans) {
        correctCount++;
        score += 25;
        scoreDisplay.textContent = score;
        statusDisplay.textContent = 'вњ… Р’РµСЂРЅРѕ!';
    } else {
        statusDisplay.textContent = 'вќЊ РќРµРІРµСЂРЅРѕ! РџСЂР°РІРёР»СЊРЅС‹Р№ РѕС‚РІРµС‚: ' + p.ans;
    }
    correctDisplay.textContent = `${correctCount} / ${TOTAL}`;
    currentIndex++;
    setTimeout(showProblem, 800);
}

function endGame() {
    gameActive = false;
    clearInterval(timerInterval);
    answerInput.disabled = true;
    submitBtn.disabled = true;
    equationDisplay.textContent = 'рџЏЃ РўСЂРµРЅРёСЂРѕРІРєР° Р·Р°РІРµСЂС€РµРЅР°!';
    statusDisplay.textContent = `РџСЂР°РІРёР»СЊРЅРѕ: ${correctCount} РёР· ${TOTAL} | РЎС‡С‘С‚: ${score}`;
    progressText.textContent = 'Р“РѕС‚РѕРІРѕ!';
    if (!saved) {
        saved = true;
        const formData = new FormData();
        formData.append('score', score);
        fetch('math.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) bestDisplay.textContent = data.best; })
            .catch(() => {});
    }
}

submitBtn.addEventListener('click', submitAnswer);
answerInput.addEventListener('keydown', e => { if (e.key === 'Enter') submitAnswer(); });

resetGame();
</script></body></html>
