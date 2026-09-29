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
						<?= htmlspecialchars($image['created_at']) ?>
					</p>

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

				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
					<div style="display: flex; align-items: center; gap: 15px;">
						<span id="likes-count-<?= $image['id'] ?>" style="font-weight: bold;"><?= $image['likes_count'] ?>
							J'aime</span>

						<?php if (isset($_SESSION['user_id'])): ?>
							<button onclick="toggleLike(<?= $image['id'] ?>)" id="btn-like-<?= $image['id'] ?>"
								style="padding: 5px 10px; background-color: <?= $image['user_liked'] ? 'var(--accent)' : 'var(--primary)' ?>; color: white; border: none; cursor: pointer; border-radius: 4px;">
								<?= $image['user_liked'] ? 'Je n\'aime plus' : 'J\'aime' ?>
							</button>
						<?php endif; ?>
					</div>

					<?php if (isset($_SESSION['user_id'])): ?>
						<button onclick="reportPost(<?= $image['id'] ?>)"
							style="padding: 5px 10px; background-color: var(--accent); color: white; border: none; cursor: pointer; border-radius: 4px; font-size: 0.8em; font-weight: bold;"
							title="Signaler cette publication">
							Signaler
						</button>
					<?php endif; ?>
				</div>

				<div style="background-color: var(--bg); padding: 10px; border-radius: 4px;">
					<h4 style="margin-bottom: 10px;">Commentaires</h4>

					<ul id="comments-list-<?= $image['id'] ?>"
						style="list-style-type: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
						<?php if (empty($image['comments'])): ?>
							<li id="no-comment-<?= $image['id'] ?>" style="font-size: 0.9em; color: gray;">Aucun commentaire.</li>
						<?php else: ?>
							<?php foreach ($image['comments'] as $comment): ?>
								<li
									style="font-size: 0.9em; border-bottom: 1px solid #ddd; padding-bottom: 5px; word-break: break-word;">
									<strong><?= htmlspecialchars($comment['username']) ?>:</strong>
									<?= htmlspecialchars($comment['content']) ?>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>

					<?php if (isset($_SESSION['user_id'])): ?>
						<div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
							<input type="text" id="comment-input-<?= $image['id'] ?>" required maxlength="255"
								placeholder="Votre commentaire (max 255 caractères)..."
								style="flex: 1; min-width: 200px; padding: 8px; border: 1px solid var(--secondary); border-radius: 4px;"
								onkeypress="handleCommentEnter(event, <?= $image['id'] ?>)">
							<button onclick="submitComment(<?= $image['id'] ?>)"
								style="padding: 8px 15px; background-color: var(--primary); color: white; border: none; cursor: pointer; border-radius: 4px;">Envoyer</button>
						</div>
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

<script>
	const csrfToken = "<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>";

	function toggleLike(imageId) {
		fetch('/like', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ image_id: imageId, csrf_token: csrfToken })
		})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					document.getElementById(`likes-count-${imageId}`).innerText = `${data.likes_count} J'aime`;
					const btn = document.getElementById(`btn-like-${imageId}`);
					if (data.user_liked) {
						btn.innerText = "Je n'aime plus";
						btn.style.backgroundColor = "var(--accent)";
					} else {
						btn.innerText = "J'aime";
						btn.style.backgroundColor = "var(--primary)";
					}
				}
			});
	}

	function submitComment(imageId) {
		const input = document.getElementById(`comment-input-${imageId}`);
		const content = input.value.trim();

		if (!content) return;

		fetch('/comment', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ image_id: imageId, content: content, csrf_token: csrfToken })
		})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					const list = document.getElementById(`comments-list-${imageId}`);
					const noComment = document.getElementById(`no-comment-${imageId}`);
					if (noComment) noComment.remove();

					const li = document.createElement('li');
					li.style.fontSize = '0.9em';
					li.style.borderBottom = '1px solid #ddd';
					li.style.paddingBottom = '5px';
					li.style.wordBreak = 'break-word';
					li.innerHTML = `<strong>${data.username}:</strong> ${data.content}`;

					list.appendChild(li);
					input.value = '';
				}
			});
	}

	function handleCommentEnter(event, imageId) {
		if (event.key === 'Enter') {
			submitComment(imageId);
		}
	}

	function reportPost(imageId) {
		if (!confirm("Voulez-vous vraiment signaler cette publication ?")) return;

		fetch('/report', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ image_id: imageId, csrf_token: csrfToken })
		})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					alert("La publication a été signalée aux administrateurs.");
				} else {
					alert(data.error || "Une erreur est survenue.");
				}
			});
	}
</script>