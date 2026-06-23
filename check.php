<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

$supa_status = '❌';
$supa_msg = '';
try {
    $result = supabaseSelect('users', ['select' => 'id', 'limit' => 1]);
    if (!isset($result['error'])) {
        $supa_status = '✅';
        $count_resp = supabaseSelect('users', ['select' => 'id']);
        $count = is_array($count_resp) ? count($count_resp) : 0;
        $supa_msg = "Supabase работает, пользователей: $count";
    } else {
        $supa_msg = $result['error'];
    }
} catch (Exception $e) {
    $supa_msg = $e->getMessage();
}

$curl_ok = function_exists('curl_version') ? '✅ curl ' . curl_version()['version'] : '❌ curl не найден';
$sess_path = session_save_path() ?: 'по умолчанию';
$php_ver = phpversion();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Проверка системы</title>
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
    <h1>🔍 Проверка системы</h1>
    <div class="card">
        <table>
            <tr><th>Параметр</th><th>Статус</th></tr>
            <tr><td>PHP версия</td><td><?= $php_ver ?></td></tr>
            <tr><td>cURL</td><td><?= $curl_ok ?></td></tr>
            <tr><td>Supabase</td><td><?= $supa_status ?> <?= $supa_msg ?></td></tr>
            <tr><td>SUPABASE_URL</td><td><?= defined('SUPABASE_URL') ? htmlspecialchars(SUPABASE_URL) : '❌ не задан' ?></td></tr>
            <tr><td>SUPABASE_KEY</td><td><?= defined('SUPABASE_KEY') && SUPABASE_KEY ? '✅ задан' : '❌ не задан' ?></td></tr>
            <tr><td>Директория сессий</td><td><?= $sess_path ?></td></tr>
            <tr><td>Папка с сайтом</td><td><?= __DIR__ ?> (<?= is_writable(__DIR__) ? '✅ запись' : '❌ нет записи' ?>)</td></tr>
        </table>
    </div>
    <p style="text-align:center;margin-top:20px;"><a href="index.php" class="btn">На главную</a></p>
</div>
</body>
</html>
