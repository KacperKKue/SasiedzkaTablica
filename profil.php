<?php
require_once 'include/auth.php';
require_once 'include/db.php';

requireLogin();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT email, username, registered_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username']);
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== '' && $newPassword !== $confirmPassword) {
        die("Hasła się nie zgadzają.");
    }

    $sql = "UPDATE users SET username = :username";
    $params = [':username' => $newUsername];

    if ($newPassword !== '') {
        $sql .= ", password = :password";
        $params[':password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    $sql .= " WHERE id = :id";
    $params[':id'] = $user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $_SESSION['username'] = $newUsername;

    header("Location: profil");
    exit;
}

if (isset($_POST['update_avatar'])) {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/png', 'image/jpeg'];
        if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
            die("Nieprawidłowy format obrazka.");
        }

        $destPath = "users/avatars/$user_id.png";
        move_uploaded_file($_FILES['avatar']['tmp_name'], $destPath);
        header("Location: profil");
        exit;
    } else {
        die("Nie udało się przesłać avatara.");
    }
}
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
                <div class="avatar-preview" id="imagePreview" style="
            background-image: url(
                '<?= file_exists("users/avatars/$user_id.png") ? "users/avatars/$user_id.png" : "images/avatar.png" ?>'
            );
            background-size: cover;
            background-position: center;
            ">
                </div>

                <form class="avatar-form" method="post" enctype="multipart/form-data">
                    <input class="input" type="file" name="avatar" accept="image/*" />
                    <button type="submit" name="update_avatar">Zmień avatar</button>
                </form>
            </div>

            <form class="profile-form" method="post">
                <label>
                    Nazwa użytkownika:
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" />
                </label>

                <label>
                    Nowe hasło:
                    <input type="password" name="password" placeholder="••••••••" />
                </label>

                <label>
                    Potwierdź hasło:
                    <input type="password" name="confirm_password" placeholder="••••••••" />
                </label>

                <div class="readonly-info">
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Zarejestrowano:</strong> <?= htmlspecialchars($user['registered_at']) ?></p>
                </div>

                <button type="submit" name="update_profile">Zapisz zmiany</button>
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