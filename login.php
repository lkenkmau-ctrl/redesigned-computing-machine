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
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Вход</title>
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
            <a href="snake.php">🐍 Змейка</a>
            <a href="tetris.php">🧊 Тетрис</a>
            <a href="2048.php">🔢 2048</a>
            <a href="tictactoe.php">⭕ Крестики-нолики</a>
            <a href="guess.php">❓ Угадай число</a>
            <a href="memory.php">🃏 Память</a>
            <a href="clicker.php">👆 Кликер</a>
            <a href="quiz.php">📝 Викторина</a>
        </div>
    </div>
</nav>
    </div>
</header>
<div class="container">
    <div class="form-card animate-in">
        <h1>Вход</h1>
        <?php if ($error): ?><div class="msg msg-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Логин" required autocomplete="off">
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" class="btn">Войти</button>
        </form>
        <a href="register.php" class="link">Нет аккаунта? Зарегистрироваться</a>
    </div>
</div>
</body>
</html>
