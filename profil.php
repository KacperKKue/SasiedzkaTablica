<?php
require_once 'include/auth.php';
require_once 'include/db.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sąsiedzka Tablica | Profil</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/profil.css">

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
        <div class="section-title">PROFIL</div>

        <section class="profile-container">
            <div class="profile-avatar">
                <div class="avatar-preview" id="imagePreview">
                    <style>
                        <?php if (file_exists('users/avatars/' . $_SESSION["user_id"] . '.png')): ?>#imagePreview {
                            background-image: url('users/avatars/<?php echo $_SESSION["user_id"]; ?>.png') !important;
                        }

                        <?php else: ?>#imagePreview {
                            background-image: url('images/avatar.png') !important;
                        }

                        <?php endif; ?>
                    </style>
                </div>

                <input class="input" type="file" id="imageInput" accept="image/*" />
            </div>

            <form class="profile-form">
                <label>
                    Nazwa użytkownika:
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION["username"]); ?>q" />
                </label>

                <label>
                    Nowe hasło:
                    <input type="password" placeholder="••••••••" />
                </label>

                <label>
                    Potwierdź hasło:
                    <input type="password" placeholder="••••••••" />
                </label>

                <div class="readonly-info">
                    <p><strong>Email:</strong> jankowalski@email.com</p>
                    <p><strong>Zarejestrowano:</strong> 2024-12-11</p>
                </div>

                <button type="submit">Zapisz zmiany</button>
            </form>
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
    <script src="js/formImage.js"></script>
</body>

</html>

<body>