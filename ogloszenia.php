<?php
require_once 'include/db.php';

$perPage = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $perPage;

$city = $_GET['city'] ?? '';
$category = $_GET['category'] ?? '';
$name = $_GET['name'] ?? '';

$where = [];
$params = [];

if ($city !== '') {
    $where[] = 'city LIKE :city';
    $params[':city'] = '%' . $city . '%';
}
if ($category !== '') {
    $where[] = 'category LIKE :category';
    $params[':category'] = '%' . $category . '%';
}
if ($name !== '') {
    $where[] = 'title LIKE :name';
    $params[':name'] = '%' . $name . '%';
}

$where[] = 'created_at >= :min_date';
$params[':min_date'] = date('Y-m-d H:i:s', strtotime('-1 month'));

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM ads $whereSQL");
$countStmt->execute($params);
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

$sql = "SELECT * FROM ads $whereSQL ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);

foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
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

    <title>Sąsiedzka Tablica | Ogłoszenia</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico" />

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/ogloszenia.css">

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
            <a href="ogloszenia" class="active">Ogłoszenia</a>
        </nav>

        <?php include 'include/components/usermenu.php'; ?>
    </header>

    <main>
        <div class="section-title">OGŁOSZENIA</div>

        <form method="get" action="ogloszenia" class="filters">
            <input type="text" name="city" placeholder="Miasto" value="<?= htmlspecialchars($city) ?>">
            <input type="text" name="category" placeholder="Kategoria" value="<?= htmlspecialchars($category) ?>">
            <input type="text" name="name" placeholder="Nazwa" value="<?= htmlspecialchars($name) ?>">
            <input type="hidden" name="page" value="1">
            <button type="submit" class="filter-btn">Filtruj</button>
            <button type="button" class="filter-btn <?= ($city === '' && $category === '' && $name === '') ? 'inactive' : '' ?>"
                onclick="location.href='ogloszenia'" <?= ($city === '' && $category === '' && $name === '') ? 'disabled' : '' ?>>
                Wyczyść filtry
            </button>
        </form>

        <div class="ads-list">
            <?php if (empty($posts)): ?>
                <div class="no-results">Brak ogłoszeń spełniających kryteria wyszukiwania.</div>
            <?php else: ?>
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
                    ?>
                    <div class="ad-item">
                        <div class="ad-image" style="background-image: url('<?= $imageSrc ?>');">
                            <?= $imageSrc ? '' : 'Brak Obrazu' ?>
                        </div>
                        <div class="ad-content">
                            <h2 class="ad-title"><?= htmlspecialchars($post['title']) ?></h2>
                            <p class="ad-description"><?= nl2br(htmlspecialchars($post['description'])) ?></p>
                            <button class="check-btn" onclick="location.href='ogloszenie?id=<?= $postId ?>'">Sprawdź</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <button onclick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>'">&laquo;</button>
                    <button onclick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>'">&lt;</button>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <button class="<?= $i == $page ? 'active' : '' ?>" onclick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>'"><?= $i ?></button>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <button onclick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>'">&gt;</button>
                    <button onclick="location.href='?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>'">&raquo;</button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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