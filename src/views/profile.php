<h2>Mon Profil</h2>

<?php if (!empty($success)): ?>
	<div style="color: green; margin-bottom: 15px;">
		<?= htmlspecialchars($success) ?>
	</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
	<div style="color: red; margin-bottom: 15px;">
		<ul>
			<?php foreach ($errors as $error): ?>
				<li>
					<?= htmlspecialchars($error) ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>

<form action="/profile" method="POST" style="display: flex; flex-direction: column; max-width: 400px; gap: 15px;">
	<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

	<div style="display: flex; flex-direction: column; gap: 5px;">
		<label for="username">Nom d'utilisateur :</label>
		<input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>"
			required>
	</div>

	<div style="display: flex; flex-direction: column; gap: 5px;">
		<label for="email">Adresse Email :</label>
		<input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
	</div>

	<div style="display: flex; align-items: center; gap: 10px;">
		<input type="checkbox" id="notify_comments" name="notify_comments" <?= !empty($user['notify_comments']) ? 'checked' : '' ?>>
		<label for="notify_comments">Recevoir un email de notification lors d'un commentaire</label>
	</div>

	<fieldset
		style="border: 1px solid var(--secondary); padding: 15px; border-radius: 4px; display: flex; flex-direction: column; gap: 10px;">
		<legend style="padding: 0 5px; color: var(--primary);">Modifier le mot de passe (laisser vide pour ne pas
			changer)</legend>

		<div style="display: flex; flex-direction: column; gap: 5px;">
			<label for="password">Nouveau mot de passe :</label>
			<input type="password" id="password" name="password">
		</div>

		<div style="display: flex; flex-direction: column; gap: 5px;">
			<label for="password_confirm">Confirmer le nouveau mot de passe :</label>
			<input type="password" id="password_confirm" name="password_confirm">
		</div>
	</fieldset>

	<button type="submit"
		style="padding: 10px; background-color: var(--primary); color: white; border: none; cursor: pointer; border-radius: 4px;">Enregistrer
		les modifications</button>
</form>