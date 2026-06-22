<?php
require_once 'config.php';
header('Content-Type: text/plain; charset=utf-8');

if (!isset($_GET['action'])) { echo 'no action'; exit; }

if ($_GET['action'] === 'save_score' && isAuth()) {
    $game = $_GET['game'] ?? '';
    $level = (int)($_GET['level'] ?? 0);
    $points = (int)($_GET['points'] ?? 0);
    if ($game !== 'snake' && $game !== 'tetris' && $game !== 'wheel' && $game !== 'scratch') { echo 'invalid game'; exit; }
    if ($level < 0) $level = 0;
    if ($points < 0) $points = 0;

    $user_id = $_SESSION['user_id'];
    $insert = supabaseInsert('scores', [
        'user_id' => $user_id,
        'game' => $game,
        'level' => $level,
        'points' => $points
    ]);
    if (isset($insert['error'])) { echo 'error:' . $insert['error']; exit; }

    $user_resp = supabaseSelect('users', [
        'select' => 'points',
        'where' => 'id=eq.' . $user_id
    ]);
    $current = !empty($user_resp) ? (int)$user_resp[0]['points'] : 0;
    supabaseUpdate('users', ['points' => $current + $points], 'id=eq.' . $user_id);

    echo 'saved:' . $points;
    exit;
}

if ($_GET['action'] === 'get_pending' && isset($_GET['key']) && $_GET['key'] === 'supersecret123') {
    $pending = supabaseSelect('donations', [
        'select' => '*',
        'where' => 'status=eq.pending',
        'order' => 'created_at.asc'
    ]);
    echo json_encode($pending, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_GET['action'] === 'mark_done' && isset($_GET['key']) && $_GET['key'] === 'supersecret123' && isset($_GET['id'])) {
    supabaseUpdate('donations', ['status' => 'completed'], 'id=eq.' . (int)$_GET['id'] . '&status=eq.pending');
    echo 'ok';
    exit;
}

echo 'no action';
