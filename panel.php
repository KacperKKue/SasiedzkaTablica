<?php
require_once 'include/auth.php';
require_once 'include/db.php';
requireLogin();

$errors = [];
$title = $desc = $category = $city = $phone = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title    = trim($_POST["title"] ?? '');
    $desc     = trim($_POST["description"] ?? '');
    $category = trim($_POST["category"] ?? '');
    $city     = trim($_POST["city"] ?? '');
    $phone    = trim($_POST["phone"] ?? '');

    if ($title === '' || $desc === '' || $category === '' || $city === '' || $phone === '') {
        $errors[] = "Wszystkie pola muszą być wypełnione.";
    }

    if (!preg_match('/^\+?[0-9\s\-]{7,}$/', $phone)) {
        $errors[] = "Nieprawidłowy numer telefonu.";
    }

    $fileType = null;
    $hasImage = false;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $hasImage = true;
        $allowedTypes = ['image/png', 'image/jpeg'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Nieprawidłowy format obrazu. Dozwolone: PNG, JPG.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO ads (id, user_id, title, description, category, city, phone_number, created_at) 
                               VALUES (null,?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_SESSION["user_id"], $title, $desc, $category, $city, $phone]);
        $postId = $pdo->lastInsertId();

        if ($hasImage) {
            $ext = $fileType === 'image/png' ? 'png' : 'jpg';
            $uploadPath = "users/posts/$postId.$ext";

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $errors[] = "Nie udało się zapisać obrazu na serwerze.";

                $pdo->prepare("DELETE FROM ads WHERE id = ?")->execute([$postId]);
            } else {
                header("Location: panel");
                exit;
            }
        } else {
            header("Location: panel");
            exit;
        }
    }
}

$perPage = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $perPage;

$totalStmt = $pdo->query("SELECT COUNT(*) FROM ads WHERE user_id = " . $_SESSION["user_id"]);
$totalPosts = $totalStmt->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

$stmt = $pdo->prepare("SELECT * FROM ads WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Sąsiedzka Tablica | Panel</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/panel.css">

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
        <div class="section-title">DODAJ OGŁOSZENIE</div>

        <form class="container" method="post" enctype="multipart/form-data" target="_self">
            <div class="sidebar">
                <div class="image-box" id="imagePreview">Brak Obrazu</div>

                <input type="file" name="image" class="input" id="imageInput">
                <input type="text" name="category" class="input" placeholder="Kategoria">
                <input type="text" name="city" class="input" placeholder="Miasto">
                <input type="text" name="phone" class="input" placeholder="Numer telefonu">

                <button class="submit">Udostępnij</button>
            </div>

            <div class="form">
                <input type="text" name="title" class="title-input" placeholder="Tytuł">
                <textarea name="description" class="description-area" placeholder="Opis"></textarea>
            </div>
        </form>

        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="section-title">MOJE OGŁOSZENIA</div>

        <div class="ads-list">
            <?php foreach ($posts as $post): ?>
                <?php
                $postId = $post['id'];
                $imgPath = "users/posts/$postId";
                if (file_exists("$imgPath.jpg")) {
                    $imageSrc = "$imgPath.jpg";
                } elseif (file_exists("$imgPath.png")) {
                    $imageSrc = "$imgPath.png";
                } else {
                    $imageSrc = null;
                }

                $backgroundStyle = $imageSrc
                    ? "background-image: url('$imageSrc'); background-size: cover; background-position: center;"
                    : "background-color: #eee;";
                ?>
                <div class="ad-item">
                    <div class="ad-image" style="<?= $backgroundStyle ?>">
                        <?= $imageSrc ? '' : 'Brak Obrazu' ?>
                    </div>

                    <div class="ad-content">
                        <h2 class="ad-title"><?= htmlspecialchars($post['title']) ?></h2>
                        <p class="ad-description"><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                        <div class="btn-container">
                            <button class="check-btn" onclick="location.href='ogloszenie.php?id=<?= $postId ?>'">Sprawdź</button>
                            <button class="delete-btn" onclick="if(confirm('Czy na pewno chcesz usunąć to ogłoszenie?')) location.href='usun.php?id=<?= $postId ?>'">Usuń</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <button onclick="location.href='?page=1'">&laquo;</button>
                <button onclick="location.href='?page=<?= $page - 1 ?>'">&lt;</button>
            <?php endif; ?>

            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <button class="<?= $i == $page ? 'active' : '' ?>" onclick="location.href='?page=<?= $i ?>'">
                    <?= $i ?>
                </button>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <button onclick="location.href='?page=<?= $page + 1 ?>'">&gt;</button>
                <button onclick="location.href='?page=<?= $totalPages ?>'">&raquo;</button>
            <?php endif; ?>
        </div>

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