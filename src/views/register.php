<h2>Inscription</h2>

<?php if (!empty($success)): ?>
	<div style="color: green; margin-bottom: 15px;">
		<?= htmlspecialchars($success) ?>
	</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
	<div style="color: red; margin-bottom: 15px;">
		<ul>
			<?php foreach ($errors as $error): ?>
				<li><?= htmlspecialchars($error) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<form action="/register" method="POST" style="display: flex; flex-direction: column; max-width: 400px; gap: 10px;">
	<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

	<label for="username">Nom d'utilisateur :</label>
	<input type="text" id="username" name="username" value="<?= $old_username ?? '' ?>" required>

	<label for="email">Adresse Email :</label>
	<input type="email" id="email" name="email" value="<?= $old_email ?? '' ?>" required>

	<label for="password">Mot de passe :</label>
	<input type="password" id="password" name="password" required>

	<button type="submit"
		style="padding: 10px; background-color: var(--primary); color: white; border: none; cursor: pointer;">S'inscrire</button>
</form>