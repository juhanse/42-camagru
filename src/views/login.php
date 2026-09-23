<h2>Connexion</h2>

<?php if (!empty($error)): ?>
	<div style="color: red; margin-bottom: 15px;">
		<?= htmlspecialchars($error) ?>
	</div>
<?php endif; ?>

<form action="/login" method="POST" style="display: flex; flex-direction: column; max-width: 400px; gap: 10px;">
	<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

	<label for="username">Nom d'utilisateur :</label>
	<input type="text" id="username" name="username" value="<?= $old_username ?? '' ?>" required>

	<label for="password">Mot de passe :</label>
	<input type="password" id="password" name="password" required>

	<button type="submit"
		style="padding: 10px; background-color: var(--primary); color: white; border: none; cursor: pointer;">Se
		connecter</button>

	<a href="/forgot" style="text-align: center; margin-top: 10px; color: var(--primary); text-decoration: none;">Mot de
		passe oublié ?</a>
</form>