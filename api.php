<?php
require_once 'config.php';
header('Content-Type: text/plain; charset=utf-8');

if (!isset($_GET['action'])) { echo 'no action'; exit; }

$db = getDb();

if ($_GET['action'] === 'save_score' && isAuth()) {
    $game = $_GET['game'] ?? '';
    $level = (int)($_GET['level'] ?? 0);
    $points = (int)($_GET['points'] ?? 0);
    if ($game !== 'snake' && $game !== 'tetris' && $game !== 'wheel' && $game !== 'scratch') { echo 'invalid game'; exit; }
    if ($level < 0) $level = 0;
    if ($points < 0) $points = 0;

    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("INSERT INTO scores (user_id, game, level, points) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $game, $level, $points]);

    $db->prepare("UPDATE users SET points = (SELECT COALESCE(SUM(points),0) FROM scores WHERE user_id = ?) WHERE id = ?")
        ->execute([$user_id, $user_id]);

    echo 'saved:' . $points;
    exit;
}

if ($_GET['action'] === 'get_pending' && isset($_GET['key']) && $_GET['key'] === 'supersecret123') {
    $pending = $db->query("SELECT d.*, u.minecraft_nick FROM donations d JOIN users u ON d.user_id = u.id WHERE d.status = 'pending' ORDER BY d.created_at ASC")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pending, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_GET['action'] === 'mark_done' && isset($_GET['key']) && $_GET['key'] === 'supersecret123' && isset($_GET['id'])) {
    $db->prepare("UPDATE donations SET status = 'completed' WHERE id = ? AND status = 'pending'")->execute([(int)$_GET['id']]);
    echo 'ok';
    exit;
}

echo 'no action';
