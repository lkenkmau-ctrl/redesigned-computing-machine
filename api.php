<?php
require_once 'config.php';
header('Content-Type: text/plain; charset=utf-8');

if (!isset($_GET['action'])) { echo 'no action'; exit; }

if ($_GET['action'] === 'save_score' && isAuth()) {
    $game = $_GET['game'] ?? '';
    $level = (int)($_GET['level'] ?? 0);
    $points = (int)($_GET['points'] ?? 0);
    $allowed = ['snake','tetris','wheel','scratch','guess','memory','clicker','quiz','2048','tictactoe','flappy','reaction','minesweeper','hangman','simon','pong','invaders','breakout','sudoku','wordle','dino','rps','typing','color_match','balloon','whack','hanoi','connect4','math','fifteen','asteroids','pacman'];
    if (!in_array($game, $allowed)) { echo 'invalid game'; exit; }
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
    // Re-queue stale processing items (older than 5 min) back to pending
    $staleCutoff = gmdate('Y-m-d\TH:i:s\Z', time() - 300);
    supabaseUpdate('donations', ['status' => 'pending'],
        "status=eq.processing&created_at=lt.$staleCutoff");

    // Atomically mark pending as processing and return them
    $processed = supabaseUpdate('donations', ['status' => 'processing'], 'status=eq.pending');
    if (isset($processed['error'])) {
        echo 'error:' . $processed['error'];
        exit;
    }
    echo json_encode($processed, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_GET['action'] === 'mark_done' && isset($_GET['key']) && $_GET['key'] === 'supersecret123' && isset($_GET['id'])) {
    supabaseUpdate('donations', ['status' => 'completed'], 'id=eq.' . (int)$_GET['id'] . '&status=eq.processing');
    echo 'ok';
    exit;
}

echo 'no action';
