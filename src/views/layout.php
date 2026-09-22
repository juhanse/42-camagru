<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($title ?? 'Camagru') ?></title>
	<link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
	<header>
		<h1>Camagru</h1>
		<nav>
			<a href="/">Galerie</a>
		</nav>
	</header>
	<main>
		<?= $content ?? '' ?>
	</main>
	<footer>
		<p>&copy; 2026 Camagru</p>
	</footer>
</body>

</html>