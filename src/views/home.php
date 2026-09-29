<h2>Galerie publique</h2>

<div style="display: flex; flex-direction: column; gap: 30px; margin-top: 20px;">
	<?php if (empty($gallery)): ?>
		<p>Aucune image pour le moment.</p>
	<?php else: ?>
		<?php foreach ($gallery as $image): ?>
			<?php
			$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
			$fullUrl = $protocol . $_SERVER['HTTP_HOST'] . $image['file_path'];
			$encodedUrl = urlencode($fullUrl);
			$encodedText = urlencode("Découvrez cette création sur Camagru !");
			?>

			<div
				style="border: 1px solid var(--secondary); padding: 15px; border-radius: 8px; background: white; max-width: 640px; margin: 0 auto; width: 100%;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
					<p style="font-weight: bold; margin: 0;">Posté par <?= htmlspecialchars($image['username']) ?> le
						<?= htmlspecialchars($image['created_at']) ?></p>

					<div style="display: flex; gap: 10px;">
						<a href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $encodedText ?>"
							target="_blank"
							style="padding: 5px 10px; background-color: #1DA1F2; color: white; text-decoration: none; border-radius: 4px; font-size: 0.8em; font-weight: bold;">
							Twitter
						</a>
						<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" target="_blank"
							style="padding: 5px 10px; background-color: #1877F2; color: white; text-decoration: none; border-radius: 4px; font-size: 0.8em; font-weight: bold;">
							Facebook
						</a>
					</div>
				</div>

				<div style="text-align: center; margin-bottom: 15px;">
					<img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Image Camagru"
						style="width: 100%; height: auto; aspect-ratio: 4/3; object-fit: cover; border-radius: 4px;">
				</div>

				<div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
					<span style="font-weight: bold;"><?= $image['likes_count'] ?> J'aime</span>

					<?php if (isset($_SESSION['user_id'])): ?>
						<form action="/like" method="POST" style="margin: 0;">
							<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
							<input type="hidden" name="image_id" value="<?= $image['id'] ?>">
							<input type="hidden" name="page" value="<?= $page ?>">
							<button type="submit"
								style="padding: 5px 10px; background-color: <?= $image['user_liked'] ? 'var(--accent)' : 'var(--primary)' ?>; color: white; border: none; cursor: pointer; border-radius: 4px;">
								<?= $image['user_liked'] ? 'Je n\'aime plus' : 'J\'aime' ?>
							</button>
						</form>
					<?php endif; ?>
				</div>

				<div style="background-color: var(--bg); padding: 10px; border-radius: 4px;">
					<h4 style="margin-bottom: 10px;">Commentaires</h4>
					<?php if (empty($image['comments'])): ?>
						<p style="font-size: 0.9em; color: gray;">Aucun commentaire.</p>
					<?php else: ?>
						<ul style="list-style-type: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
							<?php foreach ($image['comments'] as $comment): ?>
								<li
									style="font-size: 0.9em; border-bottom: 1px solid #ddd; padding-bottom: 5px; word-break: break-word;">
									<strong><?= htmlspecialchars($comment['username']) ?>:</strong>
									<?= htmlspecialchars($comment['content']) ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if (isset($_SESSION['user_id'])): ?>
						<form action="/comment" method="POST" style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
							<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
							<input type="hidden" name="image_id" value="<?= $image['id'] ?>">
							<input type="hidden" name="page" value="<?= $page ?>">
							<input type="text" name="content" required maxlength="255"
								placeholder="Votre commentaire (max 255 caractères)..."
								style="flex: 1; min-width: 200px; padding: 8px; border: 1px solid var(--secondary); border-radius: 4px;">
							<button type="submit"
								style="padding: 8px 15px; background-color: var(--primary); color: white; border: none; cursor: pointer; border-radius: 4px;">Envoyer</button>
						</form>
					<?php else: ?>
						<p style="font-size: 0.8em; margin-top: 10px; color: var(--primary);">Connectez-vous pour aimer ou
							commenter.</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<?php if (isset($totalPages) && $totalPages > 1): ?>
	<div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin-top: 30px;">
		<?php for ($i = 1; $i <= $totalPages; $i++): ?>
			<a href="/?page=<?= $i ?>"
				style="padding: 8px 12px; background-color: <?= $page === $i ? 'var(--accent)' : 'var(--primary)' ?>; color: white; text-decoration: none; border-radius: 4px;"><?= $i ?></a>
		<?php endfor; ?>
	</div>
<?php endif; ?>