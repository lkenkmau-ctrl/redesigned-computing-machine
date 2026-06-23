<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

$supa_status = 'вќЊ';
$supa_msg = '';
try {
    $result = supabaseSelect('users', ['select' => 'id', 'limit' => 1]);
    if (!isset($result['error'])) {
        $supa_status = 'вњ…';
        $count_resp = supabaseSelect('users', ['select' => 'id']);
        $count = is_array($count_resp) ? count($count_resp) : 0;
        $supa_msg = "Supabase СЂР°Р±РѕС‚Р°РµС‚, РїРѕР»СЊР·РѕРІР°С‚РµР»РµР№: $count";
    } else {
        $supa_msg = $result['error'];
    }
} catch (Exception $e) {
    $supa_msg = $e->getMessage();
}

$curl_ok = function_exists('curl_version') ? 'вњ… curl ' . curl_version()['version'] : 'вќЊ curl РЅРµ РЅР°Р№РґРµРЅ';
$sess_path = session_save_path() ?: 'РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ';
$php_ver = phpversion();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>РџСЂРѕРІРµСЂРєР° СЃРёСЃС‚РµРјС‹</title>
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
            <a href="snake.php">🐍 Змейка</a>
            <a href="tetris.php">🧊 Тетрис</a>
            <a href="2048.php">🔢 2048</a>
            <a href="tictactoe.php">⭕ Крестики-нолики</a>
            <a href="guess.php">❓ Угадай число</a>
            <a href="memory.php">🃏 Память</a>
            <a href="clicker.php">👆 Кликер</a>
            <a href="quiz.php">📝 Викторина</a>
            <a href="flappy.php">🐦 Flappy Bird</a>
            <a href="reaction.php">⚡ Reaction Test</a>
            <a href="minesweeper.php">💣 Сапёр</a>
            <a href="hangman.php">👻 Виселица</a>
            <a href="simon.php">🔴 Саймон</a>
            <a href="pong.php">🏓 Понг</a>
            <a href="invaders.php">👾 Инвейдеры</a>
            <a href="breakout.php">🧱 Арканоид</a>
            <a href="sudoku.php">🧩 Судоку</a>
            <a href="wordle.php">🔤 Вордли</a>
            <a href="dino.php">🦖 Динозаврик</a>
            <a href="rps.php">✊ Камень-Ножницы</a>
            <a href="typing.php">⌨️ Печать</a>
            <a href="color_match.php">🎨 Цвет</a>
            <a href="balloon.php">🎈 Шарики</a>
            <a href="whack.php">🔨 Крот</a>
            <a href="hanoi.php">🗼 Ханой</a>
            <a href="connect4.php">🔴 4 в ряд</a>
            <a href="math.php">🧮 Математика</a>
            <a href="fifteen.php">🧩 Пятнашки</a>
            <a href="asteroids.php">☄️ Астероиды</a>
            <a href="pacman.php">👾 Пакман</a>
        </div>
    </div>
</nav>
    </div>
</header>
<div class="container">
    <h1>рџ”Ќ РџСЂРѕРІРµСЂРєР° СЃРёСЃС‚РµРјС‹</h1>
    <div class="card">
        <table>
            <tr><th>РџР°СЂР°РјРµС‚СЂ</th><th>РЎС‚Р°С‚СѓСЃ</th></tr>
            <tr><td>PHP РІРµСЂСЃРёСЏ</td><td><?= $php_ver ?></td></tr>
            <tr><td>cURL</td><td><?= $curl_ok ?></td></tr>
            <tr><td>Supabase</td><td><?= $supa_status ?> <?= $supa_msg ?></td></tr>
            <tr><td>SUPABASE_URL</td><td><?= defined('SUPABASE_URL') ? htmlspecialchars(SUPABASE_URL) : 'вќЊ РЅРµ Р·Р°РґР°РЅ' ?></td></tr>
            <tr><td>SUPABASE_KEY</td><td><?= defined('SUPABASE_KEY') && SUPABASE_KEY ? 'вњ… Р·Р°РґР°РЅ' : 'вќЊ РЅРµ Р·Р°РґР°РЅ' ?></td></tr>
            <tr><td>Р”РёСЂРµРєС‚РѕСЂРёСЏ СЃРµСЃСЃРёР№</td><td><?= $sess_path ?></td></tr>
            <tr><td>РџР°РїРєР° СЃ СЃР°Р№С‚РѕРј</td><td><?= __DIR__ ?> (<?= is_writable(__DIR__) ? 'вњ… Р·Р°РїРёСЃСЊ' : 'вќЊ РЅРµС‚ Р·Р°РїРёСЃРё' ?>)</td></tr>
        </table>
    </div>
    <p style="text-align:center;margin-top:20px;"><a href="index.php" class="btn">РќР° РіР»Р°РІРЅСѓСЋ</a></p>
</div>
</body>
</html>
