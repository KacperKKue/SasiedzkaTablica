<?php
require_once 'include/auth.php';
require_once 'include/db.php';

requireNotLoggedIn();

$error = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    // Walidacja
    if ($email === '' || $password === '') {
        $errors[] = "Podaj adres e-mail i hasło.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nieprawidłowy format e-maila.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Weryfikacja hasła
        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["registered"] = $user["registered"];

            header("Location: index");
            exit;
        } else {
            $errors[] = "Nieprawidłowy e-mail lub hasło.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sąsiedzka Tablica | Logowanie</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">

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
        <form class="login-form" method="post" target="_self">
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Hasło" required />
            <label class="remember">
                <input type="checkbox" />
                Zapamiętaj mnie
            </label>
            <button type="submit">Zaloguj</button>
            <div class="register-link">
                Nie masz konta? <a href="rejestracja">Zarejestruj się</a>
            </div>
            <?php if (!empty($errors)) : ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error) : ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </form>
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