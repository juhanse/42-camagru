<h2>Panneau d'Administration - Signalements</h2>

<div style="display: flex; flex-direction: column; gap: 30px; margin-top: 20px;">
	<?php if (empty($reportedImages)): ?>
		<p>Aucun signalement en attente.</p>
	<?php else: ?>
		<?php foreach ($reportedImages as $image): ?>
			<div
				style="border: 1px solid var(--accent); padding: 15px; border-radius: 8px; background: white; max-width: 640px; margin: 0 auto; width: 100%;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
					<p style="font-weight: bold; margin: 0; color: var(--accent);">Signalements :
						<?= htmlspecialchars($image['report_count']) ?>
					</p>
					<p style="font-size: 0.9em; margin: 0;">Posté par <?= htmlspecialchars($image['username']) ?></p>
				</div>

				<div style="text-align: center; margin-bottom: 15px;">
					<img src="<?= htmlspecialchars($image['file_path']) ?>"
						style="width: 100%; height: auto; aspect-ratio: 4/3; object-fit: cover; border-radius: 4px;">
				</div>

				<div style="display: flex; gap: 15px; justify-content: center;">
					<form action="/admin/ignore" method="POST" style="margin: 0;">
						<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
						<input type="hidden" name="image_id" value="<?= $image['id'] ?>">
						<button type="submit"
							style="padding: 10px 20px; background-color: var(--secondary); color: var(--text); border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">
							Ignorer
						</button>
					</form>

					<form action="/admin/delete" method="POST" style="margin: 0;"
						onsubmit="return confirm('Supprimer définitivement cette image ?');">
						<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
						<input type="hidden" name="image_id" value="<?= $image['id'] ?>">
						<button type="submit"
							style="padding: 10px 20px; background-color: var(--accent); color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">
							Supprimer
						</button>
					</form>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>