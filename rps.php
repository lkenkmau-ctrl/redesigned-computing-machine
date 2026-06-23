<?php
require_once 'config.php';
requireAuth();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['score'])) {
    $score = (int)$_POST['score'];
    supabaseInsert('game_scores', ['user_id' => $user_id, 'game' => 'rps', 'score' => $score, 'created_at' => date('c')]);
    $bests = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.rps", 'order' => 'score.desc', 'limit' => 1]);
    $best = !empty($bests) && !isset($bests['error']) ? $bests[0]['score'] : 0;
    echo json_encode(['best' => $best]); exit;
}
$bestData = supabaseSelect('game_scores', ['select' => 'score', 'where' => "user_id=eq.$user_id&game=eq.rps", 'order' => 'score.desc', 'limit' => 1]);
$bestScore = !empty($bestData) && !isset($bestData['error']) ? $bestData[0]['score'] : 0;
?>
<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Камень-Ножницы-Бумага — DonateCraft</title><link rel="stylesheet" href="style.css"><style>
.rps-score { display: flex; gap: 30px; justify-content: center; align-items: center; margin: 16px 0; }
.rps-score-item { text-align: center; }
.rps-score-item .lbl { font-size: 13px; color: #6b5a40; }
.rps-score-item .val { font-size: 36px; font-weight: 800; color: #ff9900; display: block; }
.rps-score-item.player .val { color: #44bbff; }
.rps-score-item.computer .val { color: #ff6644; }
.rps-choices { display: flex; gap: 16px; justify-content: center; margin: 10px 0; min-height: 70px; }
.rps-choice { width: 64px; height: 64px; border-radius: 12px; background: rgba(40,22,5,0.8); border: 2px solid rgba(255,136,0,0.15); display: flex; align-items: center; justify-content: center; font-size: 36px; transition: all 0.3s; }
.rps-choice.picked { border-color: #ff8800; box-shadow: 0 0 20px rgba(255,136,0,0.3); transform: scale(1.1); }
.rps-choice.win { border-color: #44cc44; box-shadow: 0 0 20px rgba(68,204,68,0.3); }
.rps-choice.lose { border-color: #ff4444; box-shadow: 0 0 20px rgba(255,68,68,0.3); }
.rps-choice.draw { border-color: #ffcc00; box-shadow: 0 0 20px rgba(255,204,0,0.3); }
.rps-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin: 16px 0; }
.rps-btn { font-size: 18px; padding: 14px 24px; min-width: 140px; cursor: pointer; }
.rps-result { font-size: 22px; font-weight: 700; margin: 10px 0; min-height: 40px; }
#statusMsg { font-size: 16px; min-height: 30px; margin: 8px 0; }
.rps-round { color: #8a7a5a; font-size: 14px; }
.rps-vs { font-size: 20px; font-weight: 700; color: #6b5a40; }
@keyframes choicePop { 0% { transform: scale(0.3); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
.rps-choice.picked { animation: choicePop 0.3s ease; }
</style></head><body>
<header><div class="header-inner"><a href="index.php" class="logo-link"><?= $site_name ?></a><nav class="nav"><div class="dropdown"><button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button><div class="dropdown-content">
<a href="snake.php">?? ������</a>
<a href="tetris.php">?? ������</a>
<a href="2048.php">?? 2048</a>
<a href="tictactoe.php">? ��������-������</a>
<a href="guess.php">? ������ �����</a>
<a href="memory.php">?? ������</a>
<a href="clicker.php">?? ������</a>
<a href="quiz.php">?? ���������</a>
<a href="flappy.php">?? Flappy Bird</a>
<a href="reaction.php">? Reaction Test</a>
<a href="minesweeper.php">?? ����</a>
<a href="hangman.php">?? ��������</a>
<a href="simon.php">?? ������</a>
<a href="pong.php">?? ����</a>
<a href="invaders.php">?? ���������</a>
<a href="breakout.php">?? ��������</a>
<a href="sudoku.php">?? ������</a>
<a href="wordle.php">?? ������</a>
<a href="dino.php">?? ����������</a>
<a href="rps.php">? ������-�������</a>
<a href="typing.php">?? ������</a>
<a href="color_match.php">?? ����</a>
<a href="balloon.php">?? ������</a>
<a href="whack.php">?? ����</a>
<a href="hanoi.php">?? �����</a>
<a href="connect4.php">?? 4 � ���</a>
<a href="math.php">?? ����������</a>
<a href="fifteen.php">?? ��������</a>
<a href="asteroids.php">?? ���������</a>
<a href="pacman.php">?? ������</a>
</div></div><a href="donate.php" class="btn btn-sm">💰 Магазин</a><a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a></nav></div></header>
<div class="container"><div class="game-wrapper">
<h1>✊ Камень-Ножницы-Бумага</h1>
<div class="game-info-bar">
<div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div>
<div class="game-info-item"><span class="lbl">Рекорд</span><span class="val" id="bestDisplay"><?= $bestScore ?></span></div>
</div>
<div class="rps-score">
<div class="rps-score-item player"><span class="lbl">Вы</span><span class="val" id="playerScore">0</span></div>
<div class="rps-score-item"><span class="lbl">Раунд</span><span class="val" id="roundDisplay">1</span></div>
<div class="rps-score-item computer"><span class="lbl">Компьютер</span><span class="val" id="computerScore">0</span></div>
</div>
<div class="rps-choices">
<div class="rps-choice" id="playerChoice">❓</div>
<div class="rps-vs">VS</div>
<div class="rps-choice" id="computerChoice">❓</div>
</div>
<div class="rps-result" id="resultDisplay">Нажмите кнопку, чтобы сделать ход</div>
<div class="rps-buttons">
<button class="btn rps-btn" onclick="play('камень')">✊ Камень</button>
<button class="btn rps-btn" onclick="play('бумага')">✋ Бумага</button>
<button class="btn rps-btn" onclick="play('ножницы')">✌️ Ножницы</button>
</div>
<div id="statusMsg"></div>
<div class="game-controls"><button class="btn" onclick="resetGame()">🔄 Новая игра</button></div>
</div></div>
<footer><p>DonateCraft — зарабатывай донатные поинты за мини-игры</p></footer>
<script>
const icons = { 'камень': '✊', 'бумага': '✋', 'ножницы': '✌️' };
const beats = { 'камень': 'ножницы', 'бумага': 'камень', 'ножницы': 'бумага' };

let playerWins = 0;
let computerWins = 0;
let roundsPlayed = 0;
let gameFinished = false;
const WIN_TARGET = 3;

function resetGame() {
    playerWins = 0;
    computerWins = 0;
    roundsPlayed = 0;
    gameFinished = false;
    document.getElementById('playerScore').textContent = '0';
    document.getElementById('computerScore').textContent = '0';
    document.getElementById('roundDisplay').textContent = '1';
    document.getElementById('scoreDisplay').textContent = '0';
    document.getElementById('resultDisplay').textContent = 'Нажмите кнопку, чтобы сделать ход';
    document.getElementById('statusMsg').textContent = '';
    document.getElementById('playerChoice').textContent = '❓';
    document.getElementById('playerChoice').className = 'rps-choice';
    document.getElementById('computerChoice').textContent = '❓';
    document.getElementById('computerChoice').className = 'rps-choice';
}

function play(player) {
    if (gameFinished) {
        document.getElementById('statusMsg').textContent = '⚠️ Игра завершена. Начните новую.';
        return;
    }

    const choices = ['камень', 'бумага', 'ножницы'];
    const computer = choices[Math.floor(Math.random() * 3)];

    document.getElementById('playerChoice').textContent = icons[player];
    document.getElementById('playerChoice').className = 'rps-choice picked';
    document.getElementById('computerChoice').textContent = icons[computer];
    document.getElementById('computerChoice').className = 'rps-choice picked';

    let result;
    if (player === computer) {
        result = 'draw';
    } else if (beats[player] === computer) {
        result = 'win';
    } else {
        result = 'lose';
    }

    const pEl = document.getElementById('playerChoice');
    const cEl = document.getElementById('computerChoice');

    if (result === 'win') {
        pEl.classList.add('win');
        cEl.classList.add('lose');
        playerWins++;
        document.getElementById('resultDisplay').textContent = '✅ Вы выиграли раунд! ' + icons[player] + ' бьёт ' + icons[computer];
    } else if (result === 'lose') {
        pEl.classList.add('lose');
        cEl.classList.add('win');
        computerWins++;
        document.getElementById('resultDisplay').textContent = '❌ Вы проиграли раунд! ' + icons[computer] + ' бьёт ' + icons[player];
    } else {
        pEl.classList.add('draw');
        cEl.classList.add('draw');
        document.getElementById('resultDisplay').textContent = '🤝 Ничья!';
    }

    roundsPlayed++;
    document.getElementById('playerScore').textContent = playerWins;
    document.getElementById('computerScore').textContent = computerWins;
    document.getElementById('roundDisplay').textContent = roundsPlayed + 1;

    if (playerWins >= WIN_TARGET || computerWins >= WIN_TARGET) {
        gameFinished = true;
        const score = playerWins * 100;
        document.getElementById('scoreDisplay').textContent = score;
        if (playerWins > computerWins) {
            document.getElementById('resultDisplay').textContent = '🎉 Победа! ' + playerWins + ':' + computerWins + ' — +' + score + ' очков';
        } else {
            document.getElementById('resultDisplay').textContent = '😞 Поражение ' + playerWins + ':' + computerWins;
        }
        const formData = new FormData();
        formData.append('score', score);
        fetch('rps.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.best > 0) document.getElementById('bestDisplay').textContent = data.best; })
            .catch(() => {});
    }
}
</script></body></html>
