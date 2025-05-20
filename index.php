<!DOCTYPE html>
<html lang="pl">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Sąsiedzka Tablica</title>

	<link rel="icon" type="image/x-icon" href="images/favicon.ico" />

	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/variables.css">
	<link rel="stylesheet" href="css/global.css">
	<link rel="stylesheet" href="css/index.css">

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

	<div class="hero">
		<div class="hero-text">
			<h2>Ogłoszenia po sąsiedzku</h2>
			<p>
				Lokalne ogłoszenia jednorazowe.
				Zleć komuś pracę lub znajdź dodatkowy zarobek w twojej okolicy.
			</p>
		</div>
	</div>

	<section>
		<div class="section-title">CO ROBIMY</div>
		<div class="section-content">
			<p>
				Na Sąsiedzkiej Tablicy możesz w kilka chwil opublikować jednorazowe ogłoszenie - bez opłat i bez
				komplikacji. Szukasz zgubionego kota, sprzedajesz kanapę, a może chcesz pomóc sąsiadowi?
				Nasza tablica to proste narzędzie, które łączy ludzi w Twojej okolicy. Dodajesz treść, wybierasz
				kategorię, podajesz kontakt - gotowe. Ogłoszenie pojawia się od razu i znika, gdy nie będzie już
				potrzebne.
				Wierzymy, że małe rzeczy mają znaczenie - szczególnie te, które dzieją się blisko Ciebie.
			</p>
		</div>
	</section>

	<section>
		<div class="section-title">JAK TO DZIAŁA</div>
		<div class="how-it-works">
			<div class="icon" style="background-color: #f28c8c;"></div>
			<div class="work-box">
				<strong>Praca</strong>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin semper lorem sed sapien tempor, nec
					interdum erat tincidunt.</p>
			</div>
			<div class="icon" style="background-color: var(--light-orange);"></div>
			<div class="icon" style="background-color: var(--light-lime);"></div>
		</div>
	</section>

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