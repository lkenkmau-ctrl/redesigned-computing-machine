<?php require_once 'config.php';
if (isAuth()) header('Location: profile.php');
$error = ''; $username = ''; $minecraft_nick = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $minecraft_nick = trim($_POST['minecraft_nick'] ?? '');
    if (strlen($username) < 3 || strlen($password) < 4) {
        $error = 'Логин минимум 3 символа, пароль минимум 4';
    } elseif (empty($minecraft_nick)) {
        $error = 'Укажите Minecraft ник';
    } else {
        $existing = supabaseSelect('users', [
            'select' => 'id',
            'where' => 'username=eq.' . urlencode($username)
        ]);
        if (!empty($existing)) {
            $error = 'Такой логин уже занят';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $result = supabaseInsert('users', [
                'username' => $username,
                'password' => $hash,
                'minecraft_nick' => $minecraft_nick
            ]);
            if (!empty($result) && !isset($result['error'])) {
                $_SESSION['user_id'] = $result[0]['id'];
                $_SESSION['username'] = $username;
                header('Location: profile.php');
                exit;
            }
            $error = 'Ошибка регистрации: ' . ($result['error'] ?? 'неизвестная');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Регистрация</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="header-inner">
        <a href="index.php" class="logo-link"><?= $site_name ?></a>
        <nav class="nav">
    <div class="dropdown">
        <button class="btn btn-sm dropdown-btn">🎮 Игры ▾</button>
        <div class="dropdown-content">
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
                    <a href="pacman.php">рџ‘ѕ РџР°РєРјР°РЅ</a></div>
    </div>
</nav>
    </div>
</header>
<div class="container">
    <div class="form-card animate-in">
        <h1>Регистрация</h1>
        <?php if ($error): ?><div class="msg msg-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Логин" value="<?= htmlspecialchars($username) ?>" required autocomplete="off">
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="text" name="minecraft_nick" placeholder="Minecraft ник" value="<?= htmlspecialchars($minecraft_nick) ?>" required autocomplete="off">
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        <a href="login.php" class="link">Уже есть аккаунт? Войти</a>
    </div>
</div>
</body>
</html>
