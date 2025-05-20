<?php
require_once 'include/db.php';

$perPage = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $perPage;

// Filtry (opcjonalnie – można dodać później)
$city = $_GET['city'] ?? '';
$category = $_GET['category'] ?? '';
$name = $_GET['name'] ?? '';

// Budowa zapytania z filtrami
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

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Liczba wszystkich wyników
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM ads $whereSQL");
$countStmt->execute($params);
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

// Pobranie ogłoszeń
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

		<div class="filters">
            <input type="text" name="city" placeholder="Miasto" value="<?= htmlspecialchars($city) ?>" />
            <input type="text" name="category" placeholder="Kategoria" value="<?= htmlspecialchars($category) ?>" />
            <input type="text" name="name" placeholder="Nazwa" value="<?= htmlspecialchars($name) ?>" />
            <button class="filter-btn">Filtruj</button>
        </div>

		<div class="ads-list">
			<?php foreach ($posts as $post): ?>
				<?php
				$postId = $post['id'];
				$imgPath = "users/prosts/$postId";
				if (file_exists("$imgPath.jpg")) {
					$imageSrc = "$imgPath.jpg";
				} elseif (file_exists("$imgPath.png")) {
					$imageSrc = "$imgPath.png";
				} else {
					$imageSrc = null;
				}

				?>
				<div class="ad-item">
					<div class="ad-image" style="background-image: url('$imageSrc');">
						<?= $imageSrc ? '' : 'Brak Obrazu' ?>
					</div>
					<div class="ad-content">
						<h2><?= htmlspecialchars($post['title']) ?></h2>
						<p><?= nl2br(htmlspecialchars($post['description'])) ?></p>
						<button class="check-btn" onclick="location.href='view_post.php?id=<?= $postId ?>'">Sprawdź</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

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