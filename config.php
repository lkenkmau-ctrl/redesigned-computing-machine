<?php
if (session_status() === PHP_SESSION_NONE) {
    $sessDir = __DIR__ . '/sessions';
    if (!is_dir($sessDir)) { @mkdir($sessDir, 0777, true); }
    if (is_writable($sessDir)) { session_save_path($sessDir); }
    session_start();
}

$site_name = "DonateCraft";
$db_path = __DIR__ . '/database.sqlite';

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
    'кеис_1' => ['name' => 'Обычный кейс', 'cost' => 300, 'category' => 'cases', 'description' => '1 обычный кейс со случайным содержимым', 'command' => 'p give %player% case 1'],
    'кеис_3' => ['name' => 'Набор кейсов x3', 'cost' => 700, 'category' => 'cases', 'description' => '3 обычных кейса по выгодной цене', 'command' => 'p give %player% case 3'],
    'кеис_5' => ['name' => 'Набор кейсов x5', 'cost' => 1000, 'category' => 'cases', 'description' => '5 обычных кейсов — максимальная выгода', 'command' => 'p give %player% case 5'],
    'кеис_редкий' => ['name' => 'Редкий кейс', 'cost' => 800, 'category' => 'cases', 'description' => 'Редкий кейс с шансом на легендарку', 'command' => 'p give %player% rare_case 1'],
    'кеис_легенда' => ['name' => 'Легендарный кейс', 'cost' => 2000, 'category' => 'cases', 'description' => 'Легендарный кейс с топ-лутом', 'command' => 'p give %player% legend_case 1'],
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

function getDb() {
    global $db_path;
    if (!class_exists('PDO')) {
        die('Ошибка: PDO не установлен на сервере. Включите extension=pdo и extension=pdo_sqlite в php.ini');
    }
    if (!in_array('sqlite', PDO::getAvailableDrivers())) {
        die('Ошибка: SQLite драйвер PDO не найден. Включите extension=pdo_sqlite в php.ini');
    }
    try {
        $db = new PDO("sqlite:$db_path");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            minecraft_nick TEXT NOT NULL,
            points INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        $db->exec("CREATE TABLE IF NOT EXISTS scores (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            game TEXT NOT NULL,
            level INTEGER DEFAULT 0,
            points INTEGER DEFAULT 0,
            played_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(user_id) REFERENCES users(id)
        )");
        $db->exec("CREATE TABLE IF NOT EXISTS donations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            item_key TEXT NOT NULL,
            item_name TEXT NOT NULL,
            cost INTEGER DEFAULT 0,
            command TEXT NOT NULL,
            minecraft_nick TEXT NOT NULL,
            status TEXT DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(user_id) REFERENCES users(id)
        )");
        try { $db->exec("ALTER TABLE donations ADD COLUMN cost INTEGER DEFAULT 0"); } catch (Exception $e) {} // колонка может уже существовать
        return $db;
    } catch (Exception $e) {
        die('Ошибка базы данных: ' . $e->getMessage() . '. Убедитесь, что папка с сайтом доступна для записи.');
    }
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
