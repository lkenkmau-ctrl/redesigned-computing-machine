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
        <nav class="nav"><a href="index.php" class="btn btn-sm btn-outline">На сайт</a></nav>
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
