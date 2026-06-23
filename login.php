<?php require_once 'config.php';
if (isAuth()) header('Location: profile.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!empty($username) && !empty($password)) {
        $result = supabaseSelect('users', [
            'select' => 'id,username,password',
            'where' => 'username=eq.' . urlencode($username)
        ]);
        $user = $result[0] ?? null;
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: profile.php');
            exit;
        }
        $error = 'РќРµРІРµСЂРЅС‹Р№ Р»РѕРіРёРЅ РёР»Рё РїР°СЂРѕР»СЊ';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Р’С…РѕРґ</title>
<link rel="stylesheet" href="style.css">
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
</nav>
    </div>
</header>
<div class="container">
    <div class="form-card animate-in">
        <h1>Р’С…РѕРґ</h1>
        <?php if ($error): ?><div class="msg msg-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Р›РѕРіРёРЅ" required autocomplete="off">
            <input type="password" name="password" placeholder="РџР°СЂРѕР»СЊ" required>
            <button type="submit" class="btn">Р’РѕР№С‚Рё</button>
        </form>
        <a href="register.php" class="link">РќРµС‚ Р°РєРєР°СѓРЅС‚Р°? Р—Р°СЂРµРіРёСЃС‚СЂРёСЂРѕРІР°С‚СЊСЃСЏ</a>
    </div>
</div>
</body>
</html>
