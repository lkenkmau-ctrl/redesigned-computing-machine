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
        $db = getDb();
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Такой логин уже занят';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (username, password, minecraft_nick) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $minecraft_nick]);
            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: profile.php');
            exit;
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
        <nav class="nav"><a href="index.php" class="btn btn-sm btn-outline">Главная</a></nav>
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
