<?php require_once 'config.php';
$users_resp = supabaseSelect('users', ['select' => 'id']);
$user_count = count($users_resp);
$dons_resp = supabaseSelect('donations', [
    'select' => 'id',
    'where' => 'status=eq.completed'
]);
$total_donations = count($dons_resp);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $site_name ?> - Р—Р°СЂР°Р±Р°С‚С‹РІР°Р№ РѕС‡РєРё РІ РјРёРЅРё-РёРіСЂР°С…!</title>
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
            <?php if (isAuth()): ?>
            <a href="donate.php" class="btn btn-sm">рџ’° РњР°РіР°Р·РёРЅ</a>
            <a href="profile.php" class="btn btn-sm btn-outline">рџ‘¤ РџСЂРѕС„РёР»СЊ</a>
            <?php else: ?>
            <a href="login.php" class="btn btn-sm btn-outline">Р’С…РѕРґ</a>
            <a href="register.php" class="btn btn-sm">Р РµРіРёСЃС‚СЂР°С†РёСЏ</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<div class="container">
    <section class="hero animate-in">
        <h1>Р”РѕР±СЂРѕ РїРѕР¶Р°Р»РѕРІР°С‚СЊ РЅР° DonateCraft</h1>
        <p>Р—Р°СЂР°Р±Р°С‚С‹РІР°Р№ РѕС‡РєРё, РёРіСЂР°СЏ РІ РєР»Р°СЃСЃРёС‡РµСЃРєРёРµ РјРёРЅРё-РёРіСЂС‹, Рё РѕР±РјРµРЅРёРІР°Р№ РёС… РЅР° РґРѕРЅР°С‚, РїСЂРёРІРёР»РµРіРёРё, РЅР°Р±РѕСЂС‹ Рё РєРѕСЃРјРµС‚РёРєСѓ РЅР° РЅР°С€РµРј СЃРµСЂРІРµСЂРµ Minecraft 1.16.5!</p>
        <div class="hero-buttons">
            <?php if (!isAuth()): ?>
                <a href="register.php" class="btn" style="font-size:18px;padding:14px 36px;">РќР°С‡Р°С‚СЊ РёРіСЂР°С‚СЊ</a>
                <a href="login.php" class="btn btn-outline" style="font-size:18px;padding:14px 36px;">РЈР¶Рµ РµСЃС‚СЊ Р°РєРєР°СѓРЅС‚</a>
            <?php else: ?>
                <a href="snake.php" class="btn" style="font-size:18px;padding:14px 36px;">РРіСЂР°С‚СЊ РІ Р—РјРµР№РєСѓ</a>
                <a href="tetris.php" class="btn btn-outline" style="font-size:18px;padding:14px 36px;">РРіСЂР°С‚СЊ РІ РўРµС‚СЂРёСЃ</a>
            <?php endif; ?>
        </div>
        <div style="margin-top:20px;display:flex;gap:24px;justify-content:center;color:#666;font-size:14px;">
            <span>рџ‘Ґ <?= $user_count ?> РёРіСЂРѕРєРѕРІ</span>
            <span>рџЋЃ <?= $total_donations ?> РІС‹РґР°РЅРѕ РґРѕРЅР°С‚РѕРІ</span>
        </div>
    </section>

    <section class="features">
        <div class="feature-card animate-in delay-1">
            <div class="feature-icon">рџЋ®</div>
            <h3>РњРёРЅРё-РёРіСЂС‹</h3>
            <p>РљР»Р°СЃСЃРёС‡РµСЃРєРёРµ Р—РјРµР№РєР° Рё РўРµС‚СЂРёСЃ РїСЂСЏРјРѕ РІ Р±СЂР°СѓР·РµСЂРµ. РџСЂРѕС…РѕРґРё СѓСЂРѕРІРЅРё Рё Р·Р°СЂР°Р±Р°С‚С‹РІР°Р№ РѕС‡РєРё!</p>
        </div>
        <div class="feature-card animate-in delay-2">
            <div class="feature-icon">рџ›’</div>
            <h3>РњР°РіР°Р·РёРЅ РґРѕРЅР°С‚Р°</h3>
            <p>РўСЂР°С‚СЊ РѕС‡РєРё РЅР° РїСЂРёРІРёР»РµРіРёРё, РІР°Р»СЋС‚Сѓ, РєРµР№СЃС‹, РЅР°Р±РѕСЂС‹, Р±СѓСЃС‚РµСЂС‹ Рё РєРѕСЃРјРµС‚РёРєСѓ РґР»СЏ Minecraft.</p>
        </div>
        <div class="feature-card animate-in delay-3">
            <div class="feature-icon">вљЎ</div>
            <h3>РњРіРЅРѕРІРµРЅРЅР°СЏ РІС‹РґР°С‡Р°</h3>
            <p>РџР»Р°РіРёРЅ РЅР° СЃРµСЂРІРµСЂРµ Р°РІС‚РѕРјР°С‚РёС‡РµСЃРєРё РїСЂРѕРІРµСЂСЏРµС‚ РЅРѕРІС‹Рµ РїРѕРєСѓРїРєРё Рё РІС‹РґР°С‘С‚ РїСЂРµРґРјРµС‚С‹ РІ РёРіСЂРµ.</p>
        </div>
        <div class="feature-card animate-in delay-4">
            <div class="feature-icon">рџЏ†</div>
            <h3>РЎРѕСЂРµРІРЅРѕРІР°РЅРёРµ</h3>
            <p>РЎРѕСЂРµРІРЅСѓР№СЃСЏ СЃ РґСЂСѓРіРёРјРё РёРіСЂРѕРєР°РјРё РІ С‚Р°Р±Р»РёС†Рµ Р»РёРґРµСЂРѕРІ. РЎС‚Р°РЅСЊ Р»СѓС‡С€РёРј!</p>
        </div>
    </section>

    <h2 class="animate-in">РљР°Рє СЌС‚Рѕ СЂР°Р±РѕС‚Р°РµС‚?</h2>
    <div class="steps animate-in">
        <div class="step"><div class="step-num">1</div><p>Р—Р°СЂРµРіРёСЃС‚СЂРёСЂСѓР№СЃСЏ Рё СѓРєР°Р¶Рё СЃРІРѕР№ Minecraft РЅРёРє</p></div>
        <div class="step"><div class="step-num">2</div><p>РРіСЂР°Р№ РІ Р—РјРµР№РєСѓ РёР»Рё РўРµС‚СЂРёСЃ, РїСЂРѕС…РѕРґРё СѓСЂРѕРІРЅРё</p></div>
        <div class="step"><div class="step-num">3</div><p>РџРѕР»СѓС‡Р°Р№ РѕС‡РєРё Р·Р° РєР°Р¶РґС‹Р№ РїСЂРѕР№РґРµРЅРЅС‹Р№ СѓСЂРѕРІРµРЅСЊ</p></div>
        <div class="step"><div class="step-num">4</div><p>РџРѕРєСѓРїР°Р№ РґРѕРЅР°С‚-РїСЂРµРґРјРµС‚С‹ РІ РјР°РіР°Р·РёРЅРµ</p></div>
        <div class="step"><div class="step-num">5</div><p>РђРґРјРёРЅРёСЃС‚СЂР°С‚РѕСЂ РІС‹РґР°СЃС‚ РІСЃС‘ РЅР° СЃРµСЂРІРµСЂРµ!</p></div>
    </div>

    <h2 class="animate-in">рџЋ® РРіСЂС‹</h2>
    <div class="games-grid">
        <div class="game-card animate-in delay-1">
            <div class="game-preview game-snake">рџђЌ</div>
            <div class="game-body">
                <h3>Р—РјРµР№РєР°</h3>
                <p>РЈРїСЂР°РІР»СЏР№ Р·РјРµР№РєРѕР№, СЃРѕР±РёСЂР°Р№ РµРґСѓ Рё СЃС‚Р°РЅРѕРІРёСЃСЊ РґР»РёРЅРЅРµРµ. РљР°Р¶РґС‹Рµ 5 СЃСЉРµРґРµРЅРЅС‹С… РєСѓСЃРѕС‡РєРѕРІ вЂ” РЅРѕРІС‹Р№ СѓСЂРѕРІРµРЅСЊ!</p>
                <div class="game-stats"><span>рџЋЇ +100 РѕС‡РєРѕРІ Р·Р° СѓСЂРѕРІРµРЅСЊ</span></div>
                <?php if (isAuth()): ?><a href="snake.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview game-tetris">рџ§Љ</div>
            <div class="game-body">
                <h3>РўРµС‚СЂРёСЃ</h3>
                <p>РЎРєР»Р°РґС‹РІР°Р№ РїР°РґР°СЋС‰РёРµ Р±Р»РѕРєРё, СЃРѕР±РёСЂР°Р№ Р»РёРЅРёРё. Р‘Р»РѕРєРё РїСЂРѕС…РѕРґСЏС‚ СЃРєРІРѕР·СЊ СЃС‚РµРЅС‹! РљР°Р¶РґС‹Рµ 10 Р»РёРЅРёР№ вЂ” РЅРѕРІС‹Р№ СѓСЂРѕРІРµРЅСЊ.</p>
                <div class="game-stats"><span>рџЋЇ +100 РѕС‡РєРѕРІ Р·Р° СѓСЂРѕРІРµРЅСЊ</span></div>
                <?php if (isAuth()): ?><a href="tetris.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a00,#5a2a00);">рџЋЎ</div>
            <div class="game-body">
                <h3>РљРѕР»РµСЃРѕ Р¤РѕСЂС‚СѓРЅС‹</h3>
                <p>РљСЂСѓС‚Рё РєРѕР»РµСЃРѕ Рё РІС‹РёРіСЂС‹РІР°Р№ РґРѕ 1000 РѕС‡РєРѕРІ! <strong style="color:#ffd700;">5 РїРѕРїС‹С‚РѕРє</strong> РІ РґРµРЅСЊ.</p>
                <div class="game-stats"><span>рџЋЇ Р”Рѕ 1000 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="wheel.php" class="btn btn-gold">РљСЂСѓС‚РёС‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a003a,#4a005a);">рџЋ°</div>
            <div class="game-body">
                <h3>РЎРєСЂРµС‚С‡ РљР°СЂС‚Р°</h3>
                <p>РЎС‚РёСЂР°Р№ РїРѕРєСЂС‹С‚РёРµ РјС‹С€РєРѕР№ Рё Р·Р°Р±РµСЂРё РїСЂРёР·! <strong style="color:#ffd700;">10 РєР°СЂС‚</strong> РІ РґРµРЅСЊ. Р”Рѕ 500 РѕС‡РєРѕРІ.</p>
                <div class="game-stats"><span>рџЋЇ Р”Рѕ 500 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="scratch.php" class="btn btn-purple">РћС‚РєСЂС‹С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a3a1a,#2a5a2a);">рџ”ў</div>
            <div class="game-body">
                <h3>2048</h3>
                <p>РЎРєР»Р°РґС‹РІР°Р№ РїР»РёС‚РєРё СЃ РѕРґРёРЅР°РєРѕРІС‹РјРё С‡РёСЃР»Р°РјРё, С‡С‚РѕР±С‹ РїРѕР»СѓС‡РёС‚СЊ 2048! РљР»Р°СЃСЃРёС‡РµСЃРєР°СЏ РіРѕР»РѕРІРѕР»РѕРјРєР°.</p>
                <div class="game-stats"><span>рџЋЇ РћС‡РєРё Р·Р° СЃР»РёСЏРЅРёСЏ</span></div>
                <?php if (isAuth()): ?><a href="2048.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a3a,#2a2a5a);">вќЊ</div>
            <div class="game-body">
                <h3>РљСЂРµСЃС‚РёРєРё-РЅРѕР»РёРєРё</h3>
                <p>РЎСЂР°Р¶Р°Р№СЃСЏ РїСЂРѕС‚РёРІ РР РІ РєР»Р°СЃСЃРёС‡РµСЃРєРѕР№ РёРіСЂРµ. РџРѕР±РµРґР° = 100 РѕС‡РєРѕРІ!</p>
                <div class="game-stats"><span>рџЋЇ +100 Р·Р° РїРѕР±РµРґСѓ</span></div>
                <?php if (isAuth()): ?><a href="tictactoe.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a2a1a,#5a3a2a);">вќ“</div>
            <div class="game-body">
                <h3>РЈРіР°РґР°Р№ С‡РёСЃР»Рѕ</h3>
                <p>РљРѕРјРїСЊСЋС‚РµСЂ Р·Р°РіР°РґР°Р» С‡РёСЃР»Рѕ РѕС‚ 1 РґРѕ 100. РЈРіР°РґР°Р№ РµРіРѕ! РњРµРЅСЊС€Рµ РїРѕРїС‹С‚РѕРє вЂ” Р±РѕР»СЊС€Рµ РѕС‡РєРѕРІ.</p>
                <div class="game-stats"><span>рџЋЇ Р”Рѕ 100 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="guess.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a3a,#2a3a5a);">рџѓЏ</div>
            <div class="game-body">
                <h3>РџР°РјСЏС‚СЊ</h3>
                <p>РќР°Р№РґРё РІСЃРµ РїР°СЂС‹ РєР°СЂС‚РѕС‡РµРє. Р§РµРј РјРµРЅСЊС€Рµ С…РѕРґРѕРІ вЂ” С‚РµРј Р±РѕР»СЊС€Рµ РѕС‡РєРѕРІ!</p>
                <div class="game-stats"><span>рџЋЇ РћС‡РєРё Р·Р° РїР°СЂС‹</span></div>
                <?php if (isAuth()): ?><a href="memory.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a1a,#5a2a2a);">рџ–±пёЏ</div>
            <div class="game-body">
                <h3>РљР»РёРєРµСЂ</h3>
                <p>РљР»РёРєРЅРё РєР°Рє РјРѕР¶РЅРѕ Р±РѕР»СЊС€Рµ СЂР°Р· Р·Р° 10 СЃРµРєСѓРЅРґ! 1 РєР»РёРє = 10 РѕС‡РєРѕРІ.</p>
                <div class="game-stats"><span>рџЋЇ Р”Рѕ 500+ РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="clicker.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a1a,#4a4a2a);">рџ“ќ</div>
            <div class="game-body">
                <h3>Р’РёРєС‚РѕСЂРёРЅР°</h3>
                <p>РћС‚РІРµС‚СЊ РЅР° 10 РІРѕРїСЂРѕСЃРѕРІ РёР· СЂР°Р·РЅС‹С… РєР°С‚РµРіРѕСЂРёР№. РљР°Р¶РґС‹Р№ РїСЂР°РІРёР»СЊРЅС‹Р№ РѕС‚РІРµС‚ = 10 РѕС‡РєРѕРІ!</p>
                <div class="game-stats"><span>рџЋЇ Р”Рѕ 100 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="quiz.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a3a2a,#2a5a3a);">рџђ¦</div>
            <div class="game-body">
                <h3>Flappy Bird</h3>
                <p>РџСЂРѕРІРµРґРё РїС‚РёС‡РєСѓ С‡РµСЂРµР· РІСЃРµ С‚СЂСѓР±С‹! РЈРїСЂР°РІР»РµРЅРёРµ вЂ” РїСЂРѕР±РµР» РёР»Рё РєР»РёРє.</p>
                <div class="game-stats"><span>рџЋЇ Р—Р° РїСЂРѕР»С‘С‚</span></div>
                <?php if (isAuth()): ?><a href="flappy.php" class="btn btn-blue">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a3a,#3a2a5a);">вљЎ</div>
            <div class="game-body">
                <h3>Reaction Test</h3>
                <p>РџСЂРѕРІРµСЂСЊ СЃРєРѕСЂРѕСЃС‚СЊ СЂРµР°РєС†РёРё! РќР°Р¶РјРё РЅР° Р·РµР»С‘РЅС‹Р№ РєРІР°РґСЂР°С‚ РєР°Рє РјРѕР¶РЅРѕ Р±С‹СЃС‚СЂРµРµ.</p>
                <div class="game-stats"><span>рџЋЇ Р’СЂРµРјСЏ РІ ms</span></div>
                <?php if (isAuth()): ?><a href="reaction.php" class="btn btn-purple">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a2a1a,#5a3a2a);">рџ’Ј</div>
            <div class="game-body">
                <h3>РЎР°РїС‘СЂ</h3>
                <p>РќР°Р№РґРё РІСЃРµ РјРёРЅС‹ РЅР° РїРѕР»Рµ 9Г—9. Р›РљРњ вЂ” РѕС‚РєСЂС‹С‚СЊ, РџРљРњ вЂ” С„Р»Р°Рі.</p>
                <div class="game-stats"><span>рџЏ† +10 Р·Р° РєР»РµС‚РєСѓ</span></div>
                <?php if (isAuth()): ?><a href="minesweeper.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a3a,#4a2a5a);">рџ‘»</div>
            <div class="game-body">
                <h3>Р’РёСЃРµР»РёС†Р°</h3>
                <p>РЈРіР°РґР°Р№ СЃР»РѕРІРѕ РїРѕ Р±СѓРєРІР°Рј, РїРѕРєР° С‡РµР»РѕРІРµС‡РєР° РЅРµ РїРѕРІРµСЃРёР»Рё!</p>
                <div class="game-stats"><span>рџЏ† Р—Р° СѓРіР°РґР°РЅРЅРѕРµ СЃР»РѕРІРѕ</span></div>
                <?php if (isAuth()): ?><a href="hangman.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a1a,#5a2a2a);">рџ”ґ</div>
            <div class="game-body">
                <h3>РЎР°Р№РјРѕРЅ РіРѕРІРѕСЂРёС‚</h3>
                <p>Р—Р°РїРѕРјРЅРё РїРѕСЃР»РµРґРѕРІР°С‚РµР»СЊРЅРѕСЃС‚СЊ С†РІРµС‚РѕРІ Рё РїРѕРІС‚РѕСЂРё РµС‘!</p>
                <div class="game-stats"><span>рџЏ† +50 Р·Р° СЂР°СѓРЅРґ</span></div>
                <?php if (isAuth()): ?><a href="simon.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a3a,#2a3a5a);">рџЏ“</div>
            <div class="game-body">
                <h3>РџРѕРЅРі</h3>
                <p>РљР»Р°СЃСЃРёС‡РµСЃРєРёР№ С‚РµРЅРЅРёСЃ. РЈРїСЂР°РІР»СЏР№ СЂР°РєРµС‚РєРѕР№ РјС‹С€РєРѕР№!</p>
                <div class="game-stats"><span>рџЏ† +100 Р·Р° РїРѕР±РµРґСѓ</span></div>
                <?php if (isAuth()): ?><a href="pong.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#0a0a2e,#1a1a4a);">рџ‘ѕ</div>
            <div class="game-body">
                <h3>РљРѕСЃРјРёС‡РµСЃРєРёРµ Р·Р°С…РІР°С‚С‡РёРєРё</h3>
                <p>РћС‚Р±РёРІР°Р№ Р°С‚Р°РєРё РїСЂРёС€РµР»СЊС†РµРІ РІ РєРѕСЃРјРѕСЃРµ!</p>
                <div class="game-stats"><span>рџЏ† +10 Р·Р° СѓР±РёР№СЃС‚РІРѕ</span></div>
                <?php if (isAuth()): ?><a href="invaders.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a00,#4a2a00);">рџ§±</div>
            <div class="game-body">
                <h3>РђСЂРєР°РЅРѕРёРґ</h3>
                <p>Р Р°Р·Р±РµР№ РІСЃРµ РєРёСЂРїРёС‡РёРєРё РјСЏС‡РѕРј! 3 Р¶РёР·РЅРё.</p>
                <div class="game-stats"><span>рџЏ† +10 Р·Р° РєРёСЂРїРёС‡</span></div>
                <?php if (isAuth()): ?><a href="breakout.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a1a,#2a4a2a);">рџ§©</div>
            <div class="game-body">
                <h3>РЎСѓРґРѕРєСѓ</h3>
                <p>Р—Р°РїРѕР»РЅРё СЃРµС‚РєСѓ 9Г—9 С†РёС„СЂР°РјРё РѕС‚ 1 РґРѕ 9.</p>
                <div class="game-stats"><span>рџЏ† Р”Рѕ 500 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="sudoku.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a00,#4a4a00);">рџ”¤</div>
            <div class="game-body">
                <h3>Р’РѕСЂРґР»Рё</h3>
                <p>РЈРіР°РґР°Р№ СЃР»РѕРІРѕ РёР· 5 Р±СѓРєРІ Р·Р° 6 РїРѕРїС‹С‚РѕРє!</p>
                <div class="game-stats"><span>рџЏ† Р”Рѕ 600 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="wordle.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a00,#2a4a00);">рџ¦–</div>
            <div class="game-body">
                <h3>Р”РёРЅРѕР·Р°РІСЂРёРє</h3>
                <p>РџСЂС‹РіР°Р№ С‡РµСЂРµР· РєР°РєС‚СѓСЃС‹, РєР°Рє Chrome Dino!</p>
                <div class="game-stats"><span>рџЏ† Р—Р° РїСЂРµРїСЏС‚СЃС‚РІРёСЏ</span></div>
                <?php if (isAuth()): ?><a href="dino.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a2a,#4a2a4a);">вњЉ</div>
            <div class="game-body">
                <h3>РљР°РјРµРЅСЊ-РќРѕР¶РЅРёС†С‹-Р‘СѓРјР°РіР°</h3>
                <p>РЎС‹РіСЂР°Р№ РїСЂРѕС‚РёРІ РєРѕРјРїСЊСЋС‚РµСЂР° РґРѕ 3 РїРѕР±РµРґ!</p>
                <div class="game-stats"><span>рџЏ† +100 Р·Р° РїРѕР±РµРґСѓ</span></div>
                <?php if (isAuth()): ?><a href="rps.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a2a,#2a2a4a);">вЊЁпёЏ</div>
            <div class="game-body">
                <h3>РўРµСЃС‚ РїРµС‡Р°С‚Рё</h3>
                <p>РџРµС‡Р°С‚Р°Р№ СЃР»РѕРІР° Р±С‹СЃС‚СЂРѕ Рё РїСЂР°РІРёР»СЊРЅРѕ Р·Р° 30 СЃРµРє!</p>
                <div class="game-stats"><span>рџЏ† Р—Р° СЃРёРјРІРѕР»С‹</span></div>
                <?php if (isAuth()): ?><a href="typing.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a2a1a,#4a4a2a);">рџЋЁ</div>
            <div class="game-body">
                <h3>Р¦РІРµС‚РѕРІР°СЏ СЂРµР°РєС†РёСЏ</h3>
                <p>РќР°Р¶РёРјР°Р№ РЅР° С†РІРµС‚ С‚РµРєСЃС‚Р°, Р° РЅРµ РЅР° СЃР»РѕРІРѕ!</p>
                <div class="game-stats"><span>рџЏ† +50 Р·Р° РІРµСЂРЅРѕ</span></div>
                <?php if (isAuth()): ?><a href="color_match.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#3a1a2a,#5a2a4a);">рџЋ€</div>
            <div class="game-body">
                <h3>Р›РѕРїРЅРё С€Р°СЂРёРє</h3>
                <p>Р›РѕРїР°Р№ С€Р°СЂРёРєРё СЂР°Р·РЅС‹С… С†РІРµС‚РѕРІ Р·Р° 30 СЃРµРєСѓРЅРґ!</p>
                <div class="game-stats"><span>рџЏ† Р Р°Р·РЅС‹Рµ С†РІРµС‚Р°</span></div>
                <?php if (isAuth()): ?><a href="balloon.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a3a1a,#3a5a2a);">рџ”Ё</div>
            <div class="game-body">
                <h3>РЈРґР°СЂСЊ РєСЂРѕС‚Р°</h3>
                <p>Р‘РµР№ РєСЂРѕС‚РѕРІ, РЅРѕ РёР·Р±РµРіР°Р№ Р±РѕРјР±! 30 СЃРµРєСѓРЅРґ.</p>
                <div class="game-stats"><span>рџЏ† +10 Р·Р° РєСЂРѕС‚Р°</span></div>
                <?php if (isAuth()): ?><a href="whack.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a2a2a,#2a4a4a);">рџ—ј</div>
            <div class="game-body">
                <h3>РҐР°РЅРѕР№СЃРєР°СЏ Р±Р°С€РЅСЏ</h3>
                <p>РџРµСЂРµР»РѕР¶Рё РІСЃРµ РґРёСЃРєРё СЃ РѕРґРЅРѕР№ Р±Р°С€РЅРё РЅР° РґСЂСѓРіСѓСЋ!</p>
                <div class="game-stats"><span>рџЏ† Р”Рѕ 500 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="hanoi.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#2a1a1a,#4a2a2a);">рџ”ґ</div>
            <div class="game-body">
                <h3>Р§РµС‚С‹СЂРµ РІ СЂСЏРґ</h3>
                <p>РЎРѕР±РµСЂРё 4 С„РёС€РєРё РІ СЂСЏРґ РїСЂРѕС‚РёРІ РР!</p>
                <div class="game-stats"><span>рџЏ† +200 Р·Р° РїРѕР±РµРґСѓ</span></div>
                <?php if (isAuth()): ?><a href="connect4.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-1">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a00,#2a2a00);">рџ§®</div>
            <div class="game-body">
                <h3>РњР°С‚РµРјР°С‚РёРєР°</h3>
                <p>Р РµС€Рё 20 РїСЂРёРјРµСЂРѕРІ Р·Р° РѕРіСЂР°РЅРёС‡РµРЅРЅРѕРµ РІСЂРµРјСЏ!</p>
                <div class="game-stats"><span>рџЏ† +25 Р·Р° РїСЂРёРјРµСЂ</span></div>
                <?php if (isAuth()): ?><a href="math.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-2">
            <div class="game-preview" style="background:linear-gradient(135deg,#001a2a,#002a4a);">рџ§©</div>
            <div class="game-body">
                <h3>РџСЏС‚РЅР°С€РєРё</h3>
                <p>РЎРѕР±РµСЂРё С‡РёСЃР»Р° РѕС‚ 1 РґРѕ 15 РІ РїСЂР°РІРёР»СЊРЅРѕРј РїРѕСЂСЏРґРєРµ!</p>
                <div class="game-stats"><span>рџЏ† Р”Рѕ 500 РѕС‡РєРѕРІ</span></div>
                <?php if (isAuth()): ?><a href="fifteen.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-3">
            <div class="game-preview" style="background:linear-gradient(135deg,#0a001a,#1a002a);">в„пёЏ</div>
            <div class="game-body">
                <h3>РђСЃС‚РµСЂРѕРёРґС‹</h3>
                <p>РЈРЅРёС‡С‚РѕР¶Р°Р№ Р°СЃС‚РµСЂРѕРёРґС‹ РІ РєРѕСЃРјРѕСЃРµ РёР· РїСѓС€РєРё!</p>
                <div class="game-stats"><span>рџЏ† Р—Р° СѓРЅРёС‡С‚РѕР¶РµРЅРёРµ</span></div>
                <?php if (isAuth()): ?><a href="asteroids.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
        <div class="game-card animate-in delay-4">
            <div class="game-preview" style="background:linear-gradient(135deg,#1a1a0a,#2a2a1a);">рџ‘ѕ</div>
            <div class="game-body">
                <h3>РџР°РєРјР°РЅ</h3>
                <p>РЎСЉРµС€СЊ РІСЃРµ С‚РѕС‡РєРё Рё СѓР±РµРіР°Р№ РѕС‚ РїСЂРёРІРёРґРµРЅРёР№!</p>
                <div class="game-stats"><span>рџЏ† +10 Р·Р° С‚РѕС‡РєСѓ</span></div>
                <?php if (isAuth()): ?><a href="pacman.php" class="btn">РРіСЂР°С‚СЊ</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<footer><p><?= $site_name ?> &copy; 2026 вЂ” РЎРµСЂРІРµСЂ Minecraft 1.16.5</p></footer>
</body>
</html>
