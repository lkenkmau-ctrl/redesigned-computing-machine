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
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Математика — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.math-equation { font-size: 48px; font-weight: 800; color: #ffcc33; margin: 20px 0; letter-spacing: 4px; }
.math-input { width: 160px; font-size: 32px; text-align: center; padding: 12px; margin: 10px 0; }
.timer-bar { height: 8px; background: rgba(255,136,0,0.15); border-radius: 4px; margin: 15px 0; overflow: hidden; }
.timer-fill { height: 100%; background: linear-gradient(90deg, #ff8800, #ff3333); border-radius: 4px; transition: width 0.1s linear; }
.progress-text { font-size: 14px; color: #8a7a5a; margin: 10px 0; }
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
<h1>🧮 Математический тренажёр</h1>
<div class="game-info-bar"><div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div><div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div><div class="game-info-item"><span class="lbl">Правильно</span><span class="val" id="correctDisplay">0 / 20</span></div></div>
<div class="progress-text" id="progressText">Пример 1 из 20</div>
<div class="timer-bar"><div class="timer-fill" id="timerFill" style="width:100%"></div></div>
<div class="math-equation" id="equationDisplay">23 + 17</div>
<input class="math-input" id="answerInput" type="number" placeholder="?" autofocus>
<div class="game-controls">
<button class="btn" id="submitBtn">✅ Ответить</button>
<button class="btn" onclick="resetGame()">🔄 Новая игра</button>
</div>
<div class="game-status" id="statusDisplay" style="font-size:16px;min-height:24px;margin-top:10px;color:#8a7a5a;"></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
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
    const opSymbol = p.op === '*' ? '×' : p.op;
    equationDisplay.textContent = `${p.a} ${opSymbol} ${p.b}`;
    progressText.textContent = `Пример ${currentIndex + 1} из ${TOTAL}`;
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
            statusDisplay.textContent = '⏰ Время вышло! Правильный ответ: ' + problems[currentIndex].ans;
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
        statusDisplay.textContent = '✅ Верно!';
    } else {
        statusDisplay.textContent = '❌ Неверно! Правильный ответ: ' + p.ans;
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
    equationDisplay.textContent = '🏁 Тренировка завершена!';
    statusDisplay.textContent = `Правильно: ${correctCount} из ${TOTAL} | Счёт: ${score}`;
    progressText.textContent = 'Готово!';
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
