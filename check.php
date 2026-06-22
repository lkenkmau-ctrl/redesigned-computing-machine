<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

$db_status = '❌';
$db_msg = '';
try {
    $db = getDb();
    $db_status = '✅';
    $db_msg = 'SQLite работает';
    $count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $db_msg .= ", пользователей: $count";
} catch (Exception $e) {
    $db_msg = $e->getMessage();
}

$pdo_drivers = class_exists('PDO') ? implode(', ', PDO::getAvailableDrivers()) : 'PDO не найден';
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
            <tr><td>PDO драйверы</td><td><?= $pdo_drivers ?></td></tr>
            <tr><td>База данных</td><td><?= $db_status ?> <?= $db_msg ?></td></tr>
            <tr><td>Директория сессий</td><td><?= $sess_path ?></td></tr>
            <tr><td>Папка с сайтом</td><td><?= __DIR__ ?> (<?= is_writable(__DIR__) ? '✅ запись' : '❌ нет записи' ?>)</td></tr>
        </table>
    </div>
    <p style="text-align:center;margin-top:20px;"><a href="index.php" class="btn">На главную</a></p>
</div>
</body>
</html>
