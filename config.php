<?php
if (session_status() === PHP_SESSION_NONE) {
    $sessDir = __DIR__ . '/sessions';
    if (!is_dir($sessDir)) { @mkdir($sessDir, 0777, true); }
    if (is_writable($sessDir)) { session_save_path($sessDir); }
    session_start();
}

$site_name = "DonateCraft";

define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://pnpqqxmtfjdcdczuespj.supabase.co');
define('SUPABASE_KEY', getenv('SUPABASE_KEY') ?: 'sb_publishable_rY50-AF1plRO5v54Rl_FBA_jcG77JX9');

$points_per_snake_level = 100;
$points_per_tetris_level = 100;

$wheel_max_daily = 5;
$scratch_max_daily = 10;
$wheel_segments = [0, 50, 100, 200, 300, 500, 100, 50, 200, 1000, 150, 75];
$scratch_prizes = [0, 50, 100, 200, 300, 500];

$shop_categories = [
    'privileges' => ['name' => 'Привилегии', 'icon' => '👑', 'color' => '#ffd700'],
    'currency' => ['name' => 'Валюта', 'icon' => '💎', 'color' => '#00bfff'],
    'cases' => ['name' => 'Кейсы', 'icon' => '📦', 'color' => '#ff6347'],
    'kits' => ['name' => 'Наборы', 'icon' => '🎒', 'color' => '#32cd32'],
    'boosters' => ['name' => 'Бустеры', 'icon' => '⚡', 'color' => '#ff4500'],
    'cosmetics' => ['name' => 'Косметика', 'icon' => '✨', 'color' => '#da70d6'],
];

$donate_items = [
    'rabbit' => ['name' => 'Rabbit', 'cost' => 300, 'category' => 'privileges', 'description' => 'Ранг Rabbit', 'command' => 'lp user %player% parent add rabbit'],
    'tiger' => ['name' => 'Tiger', 'cost' => 400, 'category' => 'privileges', 'description' => 'Ранг Tiger', 'command' => 'lp user %player% parent add tiger'],
    'hydra' => ['name' => 'Hydra', 'cost' => 500, 'category' => 'privileges', 'description' => 'Ранг Hydra', 'command' => 'lp user %player% parent add hydra'],
    'dhelper' => ['name' => 'DHelper', 'cost' => 600, 'category' => 'privileges', 'description' => 'Ранг DHelper', 'command' => 'lp user %player% parent add dhelper'],
    'bunny' => ['name' => 'Bunny', 'cost' => 700, 'category' => 'privileges', 'description' => 'Ранг Bunny', 'command' => 'lp user %player% parent add bunny'],
    'plus' => ['name' => 'Plus', 'cost' => 800, 'category' => 'privileges', 'description' => 'Ранг Plus', 'command' => 'lp user %player% parent add plus'],
    'pluspro' => ['name' => 'PlusPro', 'cost' => 1000, 'category' => 'privileges', 'description' => 'Ранг PlusPro', 'command' => 'lp user %player% parent add pluspro'],
    'pro' => ['name' => 'Pro', 'cost' => 1200, 'category' => 'privileges', 'description' => 'Ранг Pro', 'command' => 'lp user %player% parent add pro'],
    'pegas' => ['name' => 'Pegas', 'cost' => 1400, 'category' => 'privileges', 'description' => 'Ранг Pegas', 'command' => 'lp user %player% parent add pegas'],
    'downer' => ['name' => 'Downer', 'cost' => 1500, 'category' => 'privileges', 'description' => 'Ранг Downer', 'command' => 'lp user %player% parent add downer'],
    'cobra' => ['name' => 'Cobra', 'cost' => 1800, 'category' => 'privileges', 'description' => 'Ранг Cobra', 'command' => 'lp user %player% parent add cobra'],
    'bull' => ['name' => 'Bull', 'cost' => 2000, 'category' => 'privileges', 'description' => 'Ранг Bull', 'command' => 'lp user %player% parent add bull'],
    'avenger' => ['name' => 'Avenger', 'cost' => 2200, 'category' => 'privileges', 'description' => 'Ранг Avenger', 'command' => 'lp user %player% parent add avenger'],
    'moon' => ['name' => 'Moon', 'cost' => 2500, 'category' => 'privileges', 'description' => 'Ранг Moon', 'command' => 'lp user %player% parent add moon'],
    'god' => ['name' => 'God', 'cost' => 3000, 'category' => 'privileges', 'description' => 'Ранг God', 'command' => 'lp user %player% parent add god'],
    'dragon' => ['name' => 'Dragon', 'cost' => 3500, 'category' => 'privileges', 'description' => 'Ранг Dragon', 'command' => 'lp user %player% parent add dragon'],
    'titan' => ['name' => 'Titan', 'cost' => 4000, 'category' => 'privileges', 'description' => 'Ранг Titan', 'command' => 'lp user %player% parent add titan'],
    'hero' => ['name' => 'Hero', 'cost' => 4500, 'category' => 'privileges', 'description' => 'Ранг Hero', 'command' => 'lp user %player% parent add hero'],
    'dracula' => ['name' => 'Dracula', 'cost' => 5000, 'category' => 'privileges', 'description' => 'Ранг Dracula', 'command' => 'lp user %player% parent add dracula'],
    'vampire' => ['name' => 'Vampire', 'cost' => 5500, 'category' => 'privileges', 'description' => 'Ранг Vampire', 'command' => 'lp user %player% parent add vampire'],
    'overlord' => ['name' => 'Overlord', 'cost' => 6000, 'category' => 'privileges', 'description' => 'Ранг Overlord', 'command' => 'lp user %player% parent add overlord'],
    'magister' => ['name' => 'Magister', 'cost' => 7000, 'category' => 'privileges', 'description' => 'Ранг Magister', 'command' => 'lp user %player% parent add magister'],
    'imperator' => ['name' => 'Imperator', 'cost' => 8000, 'category' => 'privileges', 'description' => 'Ранг Imperator', 'command' => 'lp user %player% parent add imperator'],
    'angel' => ['name' => 'Angel', 'cost' => 2500, 'category' => 'privileges', 'description' => 'Ранг Angel', 'command' => 'lp user %player% parent add grok'],
    'prime' => ['name' => 'Prime', 'cost' => 2000, 'category' => 'privileges', 'description' => 'Ранг Prime', 'command' => 'lp user %player% parent add santa'],
    'sn1ck' => ['name' => 'Sn1ck', 'cost' => 1000, 'category' => 'privileges', 'description' => 'Ранг Sn1ck', 'command' => 'lp user %player% parent add sn1ck'],
    'vipe' => ['name' => 'Vipe', 'cost' => 1200, 'category' => 'privileges', 'description' => 'Ранг Vipe', 'command' => 'lp user %player% parent add vipe'],
    'tt' => ['name' => 'TT', 'cost' => 900, 'category' => 'privileges', 'description' => 'Ранг TT', 'command' => 'lp user %player% parent add tt'],
    'yt' => ['name' => 'YT', 'cost' => 900, 'category' => 'privileges', 'description' => 'Ранг YT', 'command' => 'lp user %player% parent add yt'],
    'custom' => ['name' => 'Custom', 'cost' => 3000, 'category' => 'privileges', 'description' => 'Кастомный ранг', 'command' => 'lp user %player% parent add custom'],
    'chiter' => ['name' => 'Chiter', 'cost' => 5000, 'category' => 'privileges', 'description' => 'Ранг Chiter', 'command' => 'lp user %player% parent add chiter'],
    'риллики_100' => ['name' => '100 Рилликов', 'cost' => 200, 'category' => 'currency', 'description' => '100 единиц игровой валюты', 'command' => 'p give %player% 100'],
    'риллики_500' => ['name' => '500 Рилликов', 'cost' => 800, 'category' => 'currency', 'description' => '500 единиц игровой валюты', 'command' => 'p give %player% 500'],
    'риллики_1000' => ['name' => '1000 Рилликов', 'cost' => 1500, 'category' => 'currency', 'description' => '1000 единиц игровой валюты', 'command' => 'p give %player% 1000'],
    'риллики_5000' => ['name' => '5000 Рилликов', 'cost' => 6500, 'category' => 'currency', 'description' => '5000 единиц игровой валюты', 'command' => 'p give %player% 5000'],
    'кеис_бесп_легенда' => ['name' => 'Бесплатный легендарный', 'cost' => 500, 'category' => 'cases', 'description' => 'Бесплатный легендарный кейс', 'command' => 'case give %player% donate 1'],
    'кеис_валюта' => ['name' => 'Кейс с валютой', 'cost' => 300, 'category' => 'cases', 'description' => 'Кейс с игровой валютой', 'command' => 'case give %player% money 1'],
    'кеис_риллики' => ['name' => 'Кейс с рилликами', 'cost' => 400, 'category' => 'cases', 'description' => 'Кейс с рилликами', 'command' => 'case give %player% riliks 1'],
    'кеис_супер_риллики' => ['name' => 'Супер кейс с рилликами', 'cost' => 700, 'category' => 'cases', 'description' => 'Супер кейс с рилликами', 'command' => 'case give %player% sriliks 1'],
    'кеис_супер_донат' => ['name' => 'Супер донат кейс', 'cost' => 1000, 'category' => 'cases', 'description' => 'Супер донат кейс', 'command' => 'case give %player% sdonate 1'],
    'кеис_легендарный' => ['name' => 'Легендарный кейс', 'cost' => 2000, 'category' => 'cases', 'description' => 'Легендарный кейс', 'command' => 'case give %player% legendary 1'],
    'кеис_киты' => ['name' => 'Кейс с китами', 'cost' => 1500, 'category' => 'cases', 'description' => 'Кейс с наборами', 'command' => 'case give %player% kits 1'],
    'кеис_всё_или_ничего' => ['name' => 'Кейс всё или ничего', 'cost' => 2500, 'category' => 'cases', 'description' => 'Кейс всё или ничего', 'command' => 'case give %player% allornothing 1'],
    'кеис_модер_админ' => ['name' => 'Кейс модер или админ', 'cost' => 5000, 'category' => 'cases', 'description' => 'Кейс на получение модератора или админа', 'command' => 'case give %player% moderadmin 1'],
    'кеис_лето' => ['name' => 'Летний кейс', 'cost' => 800, 'category' => 'cases', 'description' => 'Летний кейс', 'command' => 'case give %player% summer 1'],
    'набор_стартовый' => ['name' => 'Стартовый набор', 'cost' => 400, 'category' => 'kits', 'description' => 'Еда, инструменты, броня для начала игры', 'command' => 'p give %player% starter_kit 1'],
    'набор_рыцарский' => ['name' => 'Рыцарский набор', 'cost' => 1200, 'category' => 'kits', 'description' => 'Алмазная броня, меч, щит', 'command' => 'p give %player% knight_kit 1'],
    'набор_магический' => ['name' => 'Магический набор', 'cost' => 2000, 'category' => 'kits', 'description' => 'Зелья, книги зачарований, жемчуг Края', 'command' => 'p give %player% mage_kit 1'],
    'набор_строителя' => ['name' => 'Набор строителя', 'cost' => 600, 'category' => 'kits', 'description' => 'Блоки, инструменты, декорации', 'command' => 'p give %player% builder_kit 1'],
    'бустер_xp' => ['name' => 'XP Бустер x2', 'cost' => 1000, 'category' => 'boosters', 'description' => 'Удвоенный опыт на 1 час', 'command' => 'p give %player% xp_booster 1'],
    'бустер_дроп' => ['name' => 'Drop Бустер x2', 'cost' => 1500, 'category' => 'boosters', 'description' => 'Удвоенный дроп с мобов на 1 час', 'command' => 'p give %player% drop_booster 1'],
    'бустер_деньги' => ['name' => 'Money Бустер x2', 'cost' => 2000, 'category' => 'boosters', 'description' => 'Удвоенные монеты на 1 час', 'command' => 'eco give %player% booster_money 1'],
    'частицы_огня' => ['name' => 'Частицы огня', 'cost' => 500, 'category' => 'cosmetics', 'description' => 'Огненные частицы вокруг игрока', 'command' => 'p give %player% fire_particles 1'],
    'частицы_сердца' => ['name' => 'Частицы сердец', 'cost' => 500, 'category' => 'cosmetics', 'description' => 'Романтические сердечки', 'command' => 'p give %player% heart_particles 1'],
    'частицы_магии' => ['name' => 'Магические частицы', 'cost' => 800, 'category' => 'cosmetics', 'description' => 'Волшебные звёздочки', 'command' => 'p give %player% magic_particles 1'],
    'цвет_ника_золотой' => ['name' => 'Золотой ник', 'cost' => 1000, 'category' => 'cosmetics', 'description' => 'Золотой цвет ника в чате', 'command' => 'p give %player% gold_name 1'],
    'деньги_1000' => ['name' => '1000 Монет', 'cost' => 100, 'category' => 'currency', 'description' => '1000 игровых монет', 'command' => 'eco give %player% 1000'],
    'деньги_5000' => ['name' => '5000 Монет', 'cost' => 400, 'category' => 'currency', 'description' => '5000 игровых монет', 'command' => 'eco give %player% 5000'],
    'деньги_10000' => ['name' => '10000 Монет', 'cost' => 700, 'category' => 'currency', 'description' => '10000 игровых монет', 'command' => 'eco give %player% 10000'],
    'деньги_50000' => ['name' => '50000 Монет', 'cost' => 3000, 'category' => 'currency', 'description' => '50000 игровых монет — джекпот!', 'command' => 'eco give %player% 50000'],
];

function supabase($method, $table, $options = []) {
    $url = SUPABASE_URL . '/rest/v1/' . $table;
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
    ];

    $hasQuery = false;
    if (!empty($options['select'])) {
        $url .= '?select=' . $options['select'];
        $hasQuery = true;
    }
    if (!empty($options['where'])) {
        $url .= ($hasQuery ? '&' : '?') . $options['where'];
        $hasQuery = true;
    }
    if (!empty($options['order'])) {
        $url .= ($hasQuery ? '&' : '?') . 'order=' . urlencode($options['order']);
        $hasQuery = true;
    }
    if (!empty($options['limit'])) {
        $url .= ($hasQuery ? '&' : '?') . 'limit=' . (int)$options['limit'];
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    if (in_array(strtoupper($method), ['POST', 'PATCH', 'PUT'])) {
        $body = !empty($options['body']) ? json_encode($options['body']) : '{}';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $headers[] = 'Prefer: return=representation';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        return ['error' => 'Supabase connection failed'];
    }

    $data = json_decode($response, true);
    if ($httpCode >= 400) {
        return ['error' => $data['message'] ?? $data['error'] ?? "HTTP $httpCode"];
    }

    return $data;
}

function supabaseSelect($table, $options = []) {
    return supabase('GET', $table, $options);
}

function supabaseInsert($table, $data) {
    return supabase('POST', $table, ['body' => $data]);
}

function supabaseUpdate($table, $data, $where) {
    return supabase('PATCH', $table, ['body' => $data, 'where' => $where]);
}

function isAuth() {
    return isset($_SESSION['user_id']);
}

function requireAuth() {
    if (!isAuth()) {
        header('Location: login.php');
        exit;
    }
}
