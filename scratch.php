<?php require_once 'config.php'; requireAuth();
$db = getDb();
$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$stmt = $db->prepare("SELECT COALESCE(SUM(level), 0) FROM scores WHERE user_id = ? AND game = 'scratch' AND date(played_at) = ?");
$stmt->execute([$user_id, $today]);
$cards_today = (int)$stmt->fetchColumn();
$cards_left = max(0, $scratch_max_daily - $cards_today);
$total_earned = $db->prepare("SELECT COALESCE(SUM(points), 0) FROM scores WHERE user_id = ? AND game = 'scratch'");
$total_earned->execute([$user_id]);
$total_won = (int)$total_earned->fetchColumn();
$scratch_prizes_json = json_encode($scratch_prizes);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Скретч Карта</title>
<link rel="stylesheet" href="style.css">
<style>
.scratch-grid { display: grid; grid-template-columns: repeat(3, 100px); gap: 8px; justify-content: center; margin: 20px auto; }
.scratch-cell {
    width: 100px; height: 100px; background: linear-gradient(135deg, #888, #555);
    border-radius: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 36px; color: #fff; transition: all 0.4s ease;
    border: 2px solid rgba(255,255,255,0.1); user-select: none;
}
.scratch-cell:hover { transform: scale(1.05); border-color: rgba(255,255,255,0.3); }
.scratch-cell.revealed { cursor: default; transform: none; }
.scratch-cell.revealed:hover { transform: none; }
.scratch-cell.win { animation: pulse 0.6s ease; border-color: #ffd700; box-shadow: 0 0 20px rgba(255,215,0,0.5); }
@keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.1); } }
</style>
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
            <a href="profile.php" class="btn btn-sm btn-outline">Профиль</a>
            <a href="snake.php" class="btn btn-sm btn-outline">Змейка</a>
            <a href="tetris.php" class="btn btn-sm btn-outline">Тетрис</a>
            <a href="wheel.php" class="btn btn-sm btn-outline">Колесо</a>
            <a href="donate.php" class="btn btn-sm">Магазин</a>
            <a href="index.php" class="btn btn-sm btn-outline">Главная</a>
        </nav>
    </div>
</header>
<div class="container">
    <div class="game-wrapper animate-in">
        <h1>🎰 Скретч Карта</h1>
        <p style="color:#888;">Открой 3 одинаковых символа и выиграй приз! <strong style="color:#ffd700;"><?= $cards_left ?></strong> карт сегодня</p>

        <div class="game-info-bar">
            <div class="game-info-item"><span class="lbl">Карт сегодня</span><span class="val" id="cardsLeft"><?= $cards_left ?></span></div>
            <div class="game-info-item"><span class="lbl">Всего выиграно</span><span class="val" id="totalWon"><?= $total_won ?></span></div>
            <div class="game-info-item"><span class="lbl">Последний выигрыш</span><span class="val" id="lastWin">-</span></div>
        </div>

        <div class="scratch-grid" id="scratchGrid"></div>

        <div class="game-controls">
            <button id="newCardBtn" class="btn" <?= $cards_left <= 0 ? 'disabled' : '' ?>>
                <?= $cards_left > 0 ? '🃏 Новая карта' : '❌ Карты закончились' ?>
            </button>
        </div>

        <div id="result" style="font-size:22px;font-weight:700;margin-top:16px;min-height:40px;"></div>
    </div>
</div>

<script>
const prizes = <?= $scratch_prizes_json ?>;
const symbols = ['🍎','🍊','🍋','🍇','🍒','💎','7️⃣','⭐','🔔'];
let cells, revealed, canPlay, cardsLeft = <?= $cards_left ?>;
const grid = document.getElementById('scratchGrid');
const resultDiv = document.getElementById('result');
const newCardBtn = document.getElementById('newCardBtn');
const cardsLeftSpan = document.getElementById('cardsLeft');
const lastWinSpan = document.getElementById('lastWin');

function generateCard() {
    const targetSymbol = symbols[Math.floor(Math.random() * symbols.length)];
    const targetCount = 2 + Math.floor(Math.random() * 2);
    const card = [];
    for (let i = 0; i < 9; i++) {
        card.push(i < targetCount ? targetSymbol : symbols[Math.floor(Math.random() * symbols.length)]);
    }
    for (let i = card.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [card[i], card[j]] = [card[j], card[i]];
    }
    return { card, targetSymbol, targetCount };
}

let currentCard = null;

function renderCard() {
    grid.innerHTML = '';
    revealed = Array(9).fill(false);
    canPlay = true;
    currentCard = generateCard();
    resultDiv.innerHTML = '';

    for (let i = 0; i < 9; i++) {
        const cell = document.createElement('div');
        cell.className = 'scratch-cell';
        cell.dataset.index = i;
        cell.textContent = '❓';
        cell.addEventListener('click', () => revealCell(i));
        grid.appendChild(cell);
    }
}

function revealCell(index) {
    if (!canPlay || revealed[index]) return;
    const cells = grid.children;
    revealed[index] = true;
    const cell = cells[index];
    cell.textContent = currentCard.card[index];
    cell.classList.add('revealed');

    const revealedCount = revealed.filter(r => r).length;
    if (revealedCount >= 3) {
        canPlay = false;
        checkWin();
    }
}

function checkWin() {
    const counts = {};
    for (let i = 0; i < 9; i++) {
        if (revealed[i]) {
            const sym = currentCard.card[i];
            counts[sym] = (counts[sym] || 0) + 1;
        }
    }
    let maxMatch = 0;
    let bestSym = '';
    for (const [sym, count] of Object.entries(counts)) {
        if (count > maxMatch) { maxMatch = count; bestSym = sym; }
    }

    const cells = grid.children;
    if (maxMatch >= 3) {
        const prizeIdx = Math.min(maxMatch - 3, prizes.length - 1);
        const points = prizes[prizeIdx];
        for (let i = 0; i < 9; i++) {
            if (currentCard.card[i] === bestSym) {
                cells[i].classList.add('win');
            }
        }

        if (points > 0 && cardsLeft > 0) {
            fetch('api.php?action=save_score&game=scratch&level=1&points=' + points)
                .then(r => r.text())
                .then(t => {
                    resultDiv.innerHTML = '🎉 <strong style="color:#ffd700;">+' + points + '</strong> очков! (' + maxMatch + 'x ' + bestSym + ')';
                    lastWinSpan.textContent = '+' + points;
                });
        } else {
            resultDiv.innerHTML = maxMatch + 'x ' + bestSym + ' — но приз 0 😅';
        }
    } else {
        resultDiv.innerHTML = '😔 Не хватило. Нужно 3 одинаковых!';
    }

    setTimeout(() => {
        cardsLeft--;
        cardsLeftSpan.textContent = cardsLeft;
        if (cardsLeft <= 0) {
            newCardBtn.textContent = '❌ На сегодня всё';
            newCardBtn.disabled = true;
        } else {
            newCardBtn.disabled = false;
        }
    }, 500);
}

newCardBtn.addEventListener('click', () => {
    if (cardsLeft <= 0) return;
    newCardBtn.disabled = true;
    renderCard();
});

renderCard();
</script>
<footer><p><?= $site_name ?> &copy; 2026</p></footer>
</body>
</html>
