<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($title ?? 'Camagru') ?></title>
	<link rel="stylesheet" href="/public/css/style.css">
</head>

<body>
	<header>
		<h1>Camagru</h1>
		<nav>
			<a href="/">Galerie</a>
			<?php if (isset($_SESSION['user_id'])): ?>
				<?php
				require_once __DIR__ . '/../models/User.php';
				$layoutUserModel = new User();
				$layoutUser = $layoutUserModel->getUserById($_SESSION['user_id']);
				?>
				<a href="/studio">Studio</a>
				<a href="/profile">Profil</a>
				<?php if ($layoutUser && !empty($layoutUser['is_admin'])): ?>
					<a href="/admin" style="color: var(--accent);">Admin</a>
				<?php endif; ?>
				<a href="/logout">Déconnexion</a>
			<?php else: ?>
				<a href="/login">Connexion</a>
				<a href="/register">Inscription</a>
			<?php endif; ?>
		</nav>
	</header>

	<main>
		<?= $content ?? '' ?>
	</main>

	<footer>
		<p>&copy; 2026 Camagru - juhanse</p>
	</footer>
</body>

</html>