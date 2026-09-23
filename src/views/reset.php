<h2>Réinitialisation du mot de passe</h2>

<?php if (!empty($error)): ?>
	<div style="color: red; margin-bottom: 15px;">
		<?= htmlspecialchars($error) ?>
	</div>
<?php endif; ?>

<?php if (!empty($success)): ?>
	<div style="color: green; margin-bottom: 15px;">
		<?= htmlspecialchars($success) ?>
	</div>
	<a href="/login"
		style="display: inline-block; padding: 10px; background-color: var(--primary); color: white; text-decoration: none;">Aller
		à la connexion</a>
<?php elseif (!empty($token)): ?>
	<form action="/reset" method="POST" style="display: flex; flex-direction: column; max-width: 400px; gap: 10px;">
		<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
		<input type="hidden" name="token" value="<?= $token ?>">

		<label for="password">Nouveau mot de passe :</label>
		<input type="password" id="password" name="password" required>

		<label for="password_confirm">Confirmer le nouveau mot de passe :</label>
		<input type="password" id="password_confirm" name="password_confirm" required>

		<button type="submit"
			style="padding: 10px; background-color: var(--primary); color: white; border: none; cursor: pointer;">Réinitialiser</button>
	</form>
<?php endif; ?>