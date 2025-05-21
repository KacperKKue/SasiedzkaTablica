<?php
require_once 'include/auth.php';
require_once 'include/db.php';

requireLogin();

$ad_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($ad_id <= 0) {
    header("Location: ogloszenia");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM ads WHERE id = :id");
$stmt->execute(['id' => $ad_id]);
$ad = $stmt->fetch();

if (!$ad) {
    header("Location: ogloszenia");
    exit;
}

$imagePath = null;

if (file_exists("users/posts/{$ad['id']}.png")) {
    $imagePath = "users/posts/{$ad['id']}.png";
} elseif (file_exists("users/posts/{$ad['id']}.jpg")) {
    $imagePath = "users/posts/{$ad['id']}.jpg";
} elseif (file_exists("users/posts/{$ad['id']}.jpeg")) {
    $imagePath = "users/posts/{$ad['id']}.jpeg";
}

$imageExists = file_exists($imagePath);
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sąsiedzka Tablica | Ogłoszenie - </title>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/ogloszenie.css">

    <meta name="description" content="Sąsiedzka Tablica - Twoje miejsce na ogłoszenia lokalne.">
    <meta name="keywords" content="ogłoszenia, lokalne, sąsiedzka tablica, społeczność">
    <meta name="author" content="Kacper Kostera - Klonowski">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Lexend:wght@100..900&family=Madimi+One&family=Ubuntu+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    <meta name="robots" content="noindex, nofollow">

</head>

<body>

    <header>
        <div class="logo">
            <a href="index">
                <img src="images/logo.png" alt="Logo" height="32" style="margin-right: 8px;">
            </a>
        </div>
        <nav>
            <a href="index">Strona główna</a>
            <a href="ogloszenia">Ogłoszenia</a>
        </nav>

        <?php include 'include/components/usermenu.php'; ?>
    </header>

    <main>
        <div class="section-title">OGŁOSZENIE</div>

        <section class="ad-details-container">
            <div class="ad-box">
                <!-- Obraz -->
                <div class="ad-image" id="adImage" style="<?= $imagePath ? "background-image: url('$imagePath')" : "" ?>">
                    <?php if (!$imagePath): ?><span>Brak obrazu</span><?php endif; ?>
                </div>

                <div class="ad-field"><strong>Tytuł:</strong> <span id="title"><?= htmlspecialchars($ad['title']) ?></span></div>
                <div class="ad-field"><strong>Opis:</strong> <span id="description"><?= nl2br(htmlspecialchars($ad['description'])) ?></span></div>
                <div class="ad-field"><strong>Kategoria:</strong> <span id="category"><?= htmlspecialchars($ad['category']) ?></span></div>
                <div class="ad-field"><strong>Miasto:</strong> <span id="city"><?= htmlspecialchars($ad['city']) ?></span></div>
                <div class="ad-field"><strong>Numer telefonu:</strong> <span id="phone"><?= htmlspecialchars($ad['phone_number']) ?></span></div>
                <div class="ad-field"><strong>Data dodania:</strong> <span id="created-at"><?= htmlspecialchars($ad['created_at']) ?></span></div>

                <div class="ad-meta">
                    <small>ID ogłoszenia: <span id="ad-id"><?= $ad['id'] ?></span></small> |
                    <small>ID użytkownika: <span id="user-id"><?= $ad['user_id'] ?></span></small>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-text">
            Sąsiedzka Tablica - proste ogłoszenia lokalne bez zbędnych formalności. Wspierajmy się nawzajem -
            codziennie, po sąsiedzku.
        </div>
        <div class="logo">
            <img src="images/logo.png" alt="Logo" height="64">
        </div>
    </footer>

    <script src="js/menu.js"></script>
</body>

</html>

<body>