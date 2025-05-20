<?php
require_once 'include/auth.php';
requireLogin();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Sąsiedzka Tablica | Dodaj ogłoszenie</title>

	<link rel="icon" type="image/x-icon" href="images/favicon.ico" />

	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/variables.css">
	<link rel="stylesheet" href="css/global.css">
	<link rel="stylesheet" href="css/dodaj.css">

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

		<div class="container">
			<div class="sidebar">
				<div class="image-box" id="imagePreview">Brak Obrazu</div>

				<input type="file" class="input" placeholder="Obraz" id="imageInput">
				<input type="text" class="input" placeholder="Kategoria">
				<input type="text" class="input" placeholder="Miasto">
				<input type="text" class="input" placeholder="Numer telefonu">

				<button class="submit">Udostępnij</button>
			</div>

			<div class="form">
				<input type="text" class="title-input" placeholder="Tytuł">
				<textarea class="description-area" placeholder="Opis"></textarea>
			</div>
		</div>

		<div class="section-title">MOJE OGŁOSZENIA</div>

		<div class="ads-list">
			<!-- Przykładowe ogłoszenie -->
			<div class="ad-item">
				<div class="ad-image">Brak Obrazu</div>
				<div class="ad-content">
					<h2>Lorem ipsum</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin semper lorem sed sapien tempor,
						nec interdum erat tincidunt.</p>
					<button class="check-btn">Sprawdź</button>
				</div>
			</div>

			<!-- Duplikuj poniżej więcej ogłoszeń -->
			<div class="ad-item">
				<div class="ad-image">Brak Obrazu</div>
				<div class="ad-content">
					<h2>Lorem ipsum</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin semper lorem sed sapien tempor,
						nec interdum erat tincidunt.</p>
					<button class="check-btn">Sprawdź</button>
				</div>
			</div>

			<div class="ad-item">
				<div class="ad-image">Brak Obrazu</div>
				<div class="ad-content">
					<h2>Lorem ipsum</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin semper lorem sed sapien tempor,
						nec interdum erat tincidunt.</p>
					<button class="check-btn">Sprawdź</button>
				</div>
			</div>

			<!-- itd... -->
		</div>

		<div class="pagination">
			<button>&lt;&lt;</button>
			<button class="active">1</button>
			<button>2</button>
			<button>3</button>
			<button>4</button>
			<button>5</button>
			<button>6</button>
			<button>&gt;&gt;</button>
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