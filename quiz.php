<?php require_once 'config.php'; requireAuth(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Викторина</title>
<link rel="stylesheet" href="style.css">
<style>
.quiz-card { max-width: 600px; margin: 20px auto; text-align: left; }
.quiz-progress { display: flex; gap: 6px; justify-content: center; margin: 16px 0; flex-wrap: wrap; }
.quiz-dot { width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 12px; color: #555; }
.quiz-dot.correct { background: rgba(0,255,0,0.15); border-color: #00ff00; color: #00ff00; }
.quiz-dot.wrong { background: rgba(255,0,0,0.15); border-color: #ff4444; color: #ff4444; }
.quiz-dot.current { border-color: #ffd700; color: #ffd700; box-shadow: 0 0 10px rgba(255,215,0,0.3); }
.quiz-question { font-size: 20px; font-weight: 600; margin-bottom: 20px; color: #eee; line-height: 1.4; }
.quiz-option {
    display: block; width: 100%; padding: 14px 18px; margin: 8px 0;
    background: rgba(22,33,62,0.7); border: 2px solid rgba(255,255,255,0.08);
    border-radius: 10px; color: #ccc; font-size: 16px; cursor: pointer;
    transition: all 0.25s ease; text-align: left; font-family: inherit;
}
.quiz-option:hover { border-color: rgba(0,170,0,0.4); background: rgba(22,33,62,0.9); }
.quiz-option.selected { border-color: #ffd700; background: rgba(255,215,0,0.1); color: #ffd700; }
.quiz-option.correct { border-color: #00ff00; background: rgba(0,255,0,0.1); color: #00ff00; }
.quiz-option.wrong { border-color: #ff4444; background: rgba(255,0,0,0.1); color: #ff4444; }
.quiz-option:disabled { cursor: default; }
.quiz-option:disabled:hover { border-color: rgba(255,255,255,0.08); background: rgba(22,33,62,0.7); }
.quiz-option.selected:disabled:hover { border-color: #ffd700; background: rgba(255,215,0,0.1); }
.quiz-option.correct:disabled:hover { border-color: #00ff00; background: rgba(0,255,0,0.1); }
.quiz-option.wrong:disabled:hover { border-color: #ff4444; background: rgba(255,0,0,0.1); }
</style>
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <div class="dropdown">
                <button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button>
                <div class="dropdown-content">
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
                </div>
            </div>
            <a href="donate.php" class="btn btn-sm">💰 Магазин</a>
            <a href="profile.php" class="btn btn-sm btn-outline">👤 Профиль</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>❓ Викторина</h1>
        <p style="color:#888;margin-bottom:16px;">Ответь на 10 вопросов и заработай очки!</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Вопрос</span><span class="val" id="questionNum">1 / 10</span></div>
            <div class="game-info-item"><span class="lbl">Правильно</span><span class="val" id="correctDisplay">0</span></div>
            <div class="game-info-item"><span class="lbl">Счёт</span><span class="val" id="scoreDisplay">0</span></div>
        </div>

        <div class="quiz-progress" id="progressDots"></div>

        <div class="card quiz-card" id="quizCard">
            <div class="quiz-question" id="questionText"></div>
            <div id="optionsContainer"></div>
            <div class="game-controls" style="margin-top:16px;">
                <button id="nextBtn" class="btn" style="display:none;">➡ Далее</button>
                <button id="restartBtn" class="btn btn-outline" style="display:none;">🔄 Начать заново</button>
            </div>
            <div id="result" style="font-size:18px;font-weight:600;min-height:30px;text-align:center;margin-top:12px;"></div>
        </div>
    </div>
</div>

<script>
const questions = [
    {
        q: 'Какая планета самая большая в Солнечной системе?',
        opts: ['Марс', 'Юпитер', 'Сатурн', 'Нептун'],
        ans: 1
    },
    {
        q: 'Сколько костей в теле взрослого человека?',
        opts: ['106', '206', '306', '406'],
        ans: 1
    },
    {
        q: 'Какой блок в Minecraft самый прочный?',
        opts: ['Обсидиан', 'Коренная порода', 'Алмазный блок', 'Незеритовый блок'],
        ans: 1
    },
    {
        q: 'Какая самая длинная река в мире?',
        opts: ['Амазонка', 'Нил', 'Миссисипи', 'Янцзы'],
        ans: 0
    },
    {
        q: 'Сколько хромосом у человека?',
        opts: ['23', '44', '46', '48'],
        ans: 2
    },
    {
        q: 'Какой элемент обозначается символом "Fe"?',
        opts: ['Фтор', 'Фермий', 'Железо', 'Фосфор'],
        ans: 2
    },
    {
        q: 'В каком году был изобретён Minecraft?',
        opts: ['2009', '2010', '2011', '2012'],
        ans: 0
    },
    {
        q: 'Какая самая высокая гора в мире?',
        opts: ['К2', 'Эверест', 'Канченджанга', 'Лхоцзе'],
        ans: 1
    },
    {
        q: 'Сколько спутников у Марса?',
        opts: ['1', '2', '3', '0'],
        ans: 1
    },
    {
        q: 'Какой моб в Minecraft не появляется в обычном мире?',
        opts: ['Крипер', 'Скелет', 'Зомби', 'Визер-скелет'],
        ans: 3
    }
];

const questionNum = document.getElementById('questionNum');
const correctDisplay = document.getElementById('correctDisplay');
const scoreDisplay = document.getElementById('scoreDisplay');
const progressDots = document.getElementById('progressDots');
const questionText = document.getElementById('questionText');
const optionsContainer = document.getElementById('optionsContainer');
const nextBtn = document.getElementById('nextBtn');
const restartBtn = document.getElementById('restartBtn');
const resultDiv = document.getElementById('result');

let currentQ, correctCount, score, answered, saved, totalQuestions;

totalQuestions = questions.length;

function initQuiz() {
    currentQ = 0;
    correctCount = 0;
    score = 0;
    answered = false;
    saved = false;
    renderProgress();
    showQuestion();
    updateStats();
    nextBtn.style.display = 'none';
    restartBtn.style.display = 'none';
    resultDiv.innerHTML = '';
}

function renderProgress() {
    progressDots.innerHTML = '';
    for (let i = 0; i < totalQuestions; i++) {
        const dot = document.createElement('div');
        dot.className = 'quiz-dot' + (i === 0 ? ' current' : '');
        dot.textContent = i + 1;
        progressDots.appendChild(dot);
    }
}

function updateProgress() {
    const dots = progressDots.children;
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = 'quiz-dot';
        if (i < currentQ) {
            dots[i].classList.add('correct');
        } else if (i === currentQ) {
            dots[i].classList.add('current');
        }
    }
}

function updateStats() {
    questionNum.textContent = (currentQ + 1) + ' / ' + totalQuestions;
    correctDisplay.textContent = correctCount;
    scoreDisplay.textContent = score;
}

function showQuestion() {
    if (currentQ >= totalQuestions) {
        finishQuiz();
        return;
    }
    answered = false;
    const q = questions[currentQ];
    questionText.textContent = q.q;
    optionsContainer.innerHTML = '';
    nextBtn.style.display = 'none';

    q.opts.forEach((opt, i) => {
        const btn = document.createElement('button');
        btn.className = 'quiz-option';
        btn.textContent = (i + 1) + '. ' + opt;
        btn.addEventListener('click', () => selectAnswer(i));
        optionsContainer.appendChild(btn);
    });

    updateStats();
    updateProgress();
}

function selectAnswer(idx) {
    if (answered) return;
    answered = true;

    const q = questions[currentQ];
    const opts = optionsContainer.children;

    for (let i = 0; i < opts.length; i++) {
        opts[i].disabled = true;
        if (i === q.ans) opts[i].classList.add('correct');
        if (i === idx && idx !== q.ans) opts[i].classList.add('wrong');
        if (i === idx) opts[i].classList.add('selected');
    }

    if (idx === q.ans) {
        correctCount++;
        score += 10;
    }

    const dots = progressDots.children;
    dots[currentQ].className = 'quiz-dot';
    dots[currentQ].classList.add(idx === q.ans ? 'correct' : 'wrong');

    updateStats();

    currentQ++;
    if (currentQ >= totalQuestions) {
        nextBtn.textContent = '🏁 Завершить';
    } else {
        nextBtn.textContent = '➡ Далее';
    }
    nextBtn.style.display = 'inline-block';
}

function finishQuiz() {
    resultDiv.innerHTML = '🎉 Викторина завершена! Правильных ответов: <strong style="color:#00ff00;">' + correctCount + ' / ' + totalQuestions + '</strong> | +<strong style="color:#ffd700;">' + score + '</strong> очков';
    nextBtn.style.display = 'none';
    restartBtn.style.display = 'inline-block';

    if (!saved && score > 0) {
        saved = true;
        fetch('api.php?action=save_score&game=quiz&level=1&points=' + score)
            .then(r => r.text())
            .catch(() => {});
    }
}

nextBtn.addEventListener('click', () => {
    if (currentQ >= totalQuestions) {
        finishQuiz();
    } else {
        showQuestion();
    }
});

restartBtn.addEventListener('click', () => {
    initQuiz();
});

initQuiz();
</script>

<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
