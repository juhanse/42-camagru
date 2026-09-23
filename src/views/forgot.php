<h2>Mot de passe oublié</h2>

<?php if (!empty($success)): ?>
	<div style="color: green; margin-bottom: 15px;">
		<?= htmlspecialchars($success) ?>
	</div>
<?php endif; ?>

<form action="/forgot" method="POST" style="display: flex; flex-direction: column; max-width: 400px; gap: 10px;">
	<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

	<label for="email">Adresse Email :</label>
	<input type="email" id="email" name="email" required>

	<button type="submit"
		style="padding: 10px; background-color: var(--primary); color: white; border: none; cursor: pointer;">Envoyer le
		lien</button>
</form>