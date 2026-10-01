<h2>Panneau d'Administration</h2>

<div style="display: flex; flex-wrap: wrap; gap: 30px; margin-top: 20px;">

	<div style="flex: 1; min-width: 300px;">
		<h3 style="border-bottom: 2px solid var(--accent); padding-bottom: 10px; margin-bottom: 20px;">Signalements</h3>
		<div style="display: flex; flex-direction: column; gap: 30px;">
			<?php if (empty($reportedImages)): ?>
				<p>Aucun signalement en attente.</p>
			<?php else: ?>
				<?php foreach ($reportedImages as $image): ?>
					<div
						style="border: 1px solid var(--accent); padding: 15px; border-radius: 8px; background: white; width: 100%;">
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

						<div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
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
	</div>

	<div style="flex: 1; min-width: 300px;">
		<h3 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 20px;">Liste des
			utilisateurs</h3>
		<div style="background: white; border: 1px solid var(--secondary); border-radius: 8px; overflow: hidden;">
			<table style="width: 100%; border-collapse: collapse; text-align: left;">
				<thead>
					<tr style="background-color: var(--bg); border-bottom: 1px solid var(--secondary);">
						<th style="padding: 12px 15px;">Utilisateur</th>
						<th style="padding: 12px 15px;">Email</th>
						<th style="padding: 12px 15px; text-align: center;">Images</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($usersList)): ?>
						<tr>
							<td colspan="3" style="padding: 15px; text-align: center;">Aucun utilisateur.</td>
						</tr>
					<?php else: ?>
						<?php foreach ($usersList as $u): ?>
							<tr style="border-bottom: 1px solid #eee;">
								<td style="padding: 12px 15px; font-weight: bold;"><?= htmlspecialchars($u['username']) ?></td>
								<td style="padding: 12px 15px; font-size: 0.9em; word-break: break-all;">
									<?= htmlspecialchars($u['email']) ?>
								</td>
								<td style="padding: 12px 15px; text-align: center;">
									<span
										style="background-color: var(--primary); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.85em; font-weight: bold;">
										<?= $u['image_count'] ?>
									</span>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

</div>