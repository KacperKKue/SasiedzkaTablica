<?php
require_once 'include/auth.php';
require_once 'include/db.php';

requireNotLoggedIn();

$error = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "Wszystkie pola są wymagane.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nieprawidłowy adres e-mail.";
    } elseif ($password !== $confirm) {
        $errors[] = "Hasła nie są takie same.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Hasło musi mieć co najmniej 8 znaków.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Hasło musi zawierać przynajmniej jedną wielką literę.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Hasło musi zawierać przynajmniej jedną małą literę.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Hasło musi zawierać przynajmniej jedną cyfrę.";
    } else if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = "Taki użytkownik lub e-mail już istnieje.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword]);

            header("Location: login");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sąsiedzka Tablica | Rejestracja</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/rejestracja.css">

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
        <form class="register-form" method="post" target="_self">
            <input type="email" name="email" placeholder="Email" required />
            <input type="text" name="username" placeholder="Nazwa użytkownika" required />
            <input type="password" name="password" placeholder="Hasło" required />
            <input type="password" name="confirm" placeholder="Potwierdź hasło" required />
            <button type="submit">Zarejestruj</button>
            <?php
            if (!empty($errors)) {
                echo "<div class='error-messages'>";

                foreach ($errors as $error) {
                    echo "
                        <div class='error'>Błąd: $error</div>
                    ";
                }
                
                echo "</div>";
            }
            ?>
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