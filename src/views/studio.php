<div id="imageModal"
	style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 1000; justify-content: center; align-items: center; padding: 20px;">
	<span id="closeModal"
		style="position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; font-weight: bold; cursor: pointer;">&times;</span>
	<img id="modalImage" src=""
		style="max-width: 100%; max-height: 100%; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
</div>

<div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
	<div style="flex: 2; min-width: 300px; display: flex; flex-direction: column; gap: 15px;">
		<h2>Studio de Montage</h2>

		<div
			style="position: relative; width: 100%; background-color: #000; border-radius: 8px; overflow: hidden; aspect-ratio: 4/3; display: flex; justify-content: center; align-items: center;">
			<video id="videoElement" autoplay playsinline
				style="width: 100%; height: 100%; object-fit: cover; display: none;"></video>
			<img id="uploadedImage" style="width: 100%; height: 100%; object-fit: contain; display: none;"
				alt="Upload utilisateur">
			<img id="overlayFilter"
				style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: fill; pointer-events: none; display: none;"
				alt="Filtre superposé">
			<button id="activateCameraBtn"
				style="position: absolute; z-index: 10; padding: 12px 24px; font-size: 1em; font-weight: bold; background-color: var(--accent); color: white; border: none; border-radius: 4px; cursor: pointer; display: none;">
				Activez la caméra
			</button>
		</div>

		<div
			style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 20px; padding: 15px; background: white; border-radius: 8px; border: 1px solid var(--secondary);">
			<button id="captureBtn" disabled
				style="padding: 12px 24px; font-size: 1em; font-weight: bold; background-color: var(--primary); color: white; border: none; border-radius: 4px; cursor: not-allowed; opacity: 0.5; transition: all 0.2s;">
				Prendre une photo
			</button>

			<span style="font-weight: bold; color: var(--text);">OU</span>

			<label for="imageUpload"
				style="padding: 12px 24px; font-size: 1em; font-weight: bold; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; text-align: center; display: inline-block;">
				Uploader une image
			</label>
			<input type="file" id="imageUpload" accept="image/png, image/jpeg" style="display: none;">

			<button id="clearUploadBtn"
				style="display: none; padding: 12px 24px; font-size: 1em; font-weight: bold; background-color: var(--accent); color: white; border: none; border-radius: 4px; cursor: pointer;">
				Annuler
			</button>
		</div>

		<div id="statusMessage" style="padding: 10px; border-radius: 4px; display: none;"></div>

		<div
			style="margin-top: 20px; background: white; padding: 15px; border-radius: 8px; border: 1px solid var(--secondary);">
			<h3>Mes créations</h3>
			<div style="display: flex; overflow-x: auto; gap: 15px; padding-top: 15px; padding-bottom: 10px;">
				<?php if (!empty($userImages)): ?>
					<?php foreach ($userImages as $img): ?>
						<div style="position: relative; min-width: 120px; width: 120px; flex-shrink: 0;">
							<img src="<?= htmlspecialchars($img['file_path']) ?>" class="creation-thumbnail"
								style="width: 100%; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; cursor: zoom-in;"
								alt="Miniature">
							<form action="/studio/delete" method="POST"
								style="position: absolute; top: -5px; right: -5px; margin: 0; z-index: 10;">
								<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
								<input type="hidden" name="image_id" value="<?= $img['id'] ?>">
								<button type="submit"
									style="background-color: var(--accent); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;"
									title="Supprimer">×</button>
							</form>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p style="color: gray; font-size: 0.9em;">Aucune image créée pour le moment.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div
		style="flex: 1; min-width: 250px; background: white; border: 1px solid var(--secondary); border-radius: 8px; padding: 15px; display: flex; flex-direction: column;">
		<h3>Calques</h3>
		<p style="font-size: 0.9em; color: gray; margin-bottom: 15px;">Sélectionnez un calque pour activer la capture.
		</p>

		<div style="flex: 1; position: relative; min-height: 200px;">
			<div id="filtersContainer"
				style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; flex-direction: column; gap: 10px; overflow-y: auto; padding-right: 5px;">
				<?php if (!empty($filters)): ?>
					<?php foreach ($filters as $filter): ?>
						<div class="filter-option" data-src="<?= htmlspecialchars($filter) ?>"
							style="border: 2px solid transparent; border-radius: 4px; cursor: pointer; transition: 0.2s; background-color: #f1f1f1; padding: 5px; text-align: center;">
							<img src="<?= htmlspecialchars($filter) ?>"
								style="max-width: 100%; max-height: 100px; object-fit: contain;" alt="Filtre">
							<p style="font-size: 0.8em; margin-top: 5px; word-break: break-all;">
								<?= htmlspecialchars(basename($filter)) ?>
							</p>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<p>Aucun filtre trouvé dans le dossier public/filters.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<script>
	const video = document.getElementById('videoElement');
	const imageUpload = document.getElementById('imageUpload');
	const uploadedImage = document.getElementById('uploadedImage');
	const clearUploadBtn = document.getElementById('clearUploadBtn');
	const captureBtn = document.getElementById('captureBtn');
	const overlayFilter = document.getElementById('overlayFilter');
	const filters = document.querySelectorAll('.filter-option');
	const statusMessage = document.getElementById('statusMessage');
	const activateCameraBtn = document.getElementById('activateCameraBtn');

	const imageModal = document.getElementById('imageModal');
	const modalImage = document.getElementById('modalImage');
	const closeModal = document.getElementById('closeModal');
	const creationThumbnails = document.querySelectorAll('.creation-thumbnail');

	let stream = null;
	let selectedFilterUrl = null;
	let isVideoMode = true;
	let isCameraActive = false;
	const csrfToken = "<?= htmlspecialchars($_SESSION['csrf_token']) ?>";

	function startCamera() {
		if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
			navigator.mediaDevices.getUserMedia({ video: true, audio: false })
				.then(function (mediaStream) {
					stream = mediaStream;
					video.srcObject = stream;
					video.style.display = 'block';
					uploadedImage.style.display = 'none';
					activateCameraBtn.style.display = 'none';
					isVideoMode = true;
					isCameraActive = true;
					updateCaptureButtonState();
				})
				.catch(function () {
					isCameraActive = false;
					activateCameraBtn.style.display = 'block';
					updateCaptureButtonState();
				});
		} else {
			isCameraActive = false;
			activateCameraBtn.style.display = 'block';
		}
	}

	function stopCamera() {
		if (stream) {
			stream.getTracks().forEach(track => track.stop());
			stream = null;
		}
		video.style.display = 'none';
		isCameraActive = false;
	}

	function isMediaReady() {
		return (isVideoMode && isCameraActive) || (!isVideoMode && uploadedImage.style.display === 'block');
	}

	function updateCaptureButtonState() {
		if (selectedFilterUrl && isMediaReady()) {
			captureBtn.disabled = false;
			captureBtn.style.opacity = '1';
			captureBtn.style.cursor = 'pointer';
			captureBtn.style.backgroundColor = '#28a745';
		} else {
			captureBtn.disabled = true;
			captureBtn.style.opacity = '0.5';
			captureBtn.style.cursor = 'not-allowed';
			captureBtn.style.backgroundColor = 'var(--primary)';
		}
	}

	activateCameraBtn.addEventListener('click', function () {
		startCamera();
	});

	filters.forEach(filter => {
		filter.addEventListener('click', function () {
			if (!isMediaReady()) {
				return;
			}
			filters.forEach(f => f.style.borderColor = 'transparent');
			this.style.borderColor = 'var(--primary)';
			selectedFilterUrl = this.getAttribute('data-src');
			overlayFilter.src = selectedFilterUrl;
			overlayFilter.style.display = 'block';
			updateCaptureButtonState();
		});
	});

	imageUpload.addEventListener('change', function (e) {
		const file = e.target.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = function (event) {
				uploadedImage.src = event.target.result;
				stopCamera();
				isVideoMode = false;
				activateCameraBtn.style.display = 'none';
				uploadedImage.style.display = 'block';
				clearUploadBtn.style.display = 'inline-block';
				updateCaptureButtonState();
			};
			reader.readAsDataURL(file);
		}
	});

	clearUploadBtn.addEventListener('click', function () {
		imageUpload.value = '';
		uploadedImage.src = '';
		uploadedImage.style.display = 'none';
		clearUploadBtn.style.display = 'none';

		if (selectedFilterUrl) {
			filters.forEach(f => f.style.borderColor = 'transparent');
			overlayFilter.src = '';
			overlayFilter.style.display = 'none';
			selectedFilterUrl = null;
		}

		startCamera();
		updateCaptureButtonState();
	});

	captureBtn.addEventListener('click', function () {
		if (!selectedFilterUrl || !isMediaReady()) return;

		captureBtn.disabled = true;
		captureBtn.style.backgroundColor = 'var(--primary)';

		const canvas = document.createElement('canvas');
		const ctx = canvas.getContext('2d');

		if (isVideoMode) {
			canvas.width = video.videoWidth;
			canvas.height = video.videoHeight;
			ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
		} else {
			canvas.width = uploadedImage.naturalWidth;
			canvas.height = uploadedImage.naturalHeight;
			ctx.drawImage(uploadedImage, 0, 0, canvas.width, canvas.height);
		}

		const dataURL = canvas.toDataURL('image/png');

		fetch('/studio/save', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				image: dataURL,
				filter: selectedFilterUrl,
				csrf_token: csrfToken
			})
		})
			.then(response => response.json())
			.then(data => {
				statusMessage.style.display = 'block';
				if (data.success) {
					statusMessage.style.backgroundColor = '#d4edda';
					statusMessage.style.color = '#155724';
					statusMessage.innerText = 'Image sauvegardée avec succès ! (Rechargement...)';
					setTimeout(() => window.location.reload(), 1000);
				} else {
					statusMessage.style.backgroundColor = '#f8d7da';
					statusMessage.style.color = '#721c24';
					statusMessage.innerText = data.error || 'Erreur lors de la sauvegarde.';
					updateCaptureButtonState();
				}
			})
			.catch(() => {
				statusMessage.style.display = 'block';
				statusMessage.style.backgroundColor = '#f8d7da';
				statusMessage.style.color = '#721c24';
				statusMessage.innerText = 'Erreur réseau.';
				updateCaptureButtonState();
			});
	});

	creationThumbnails.forEach(thumb => {
		thumb.addEventListener('click', function () {
			modalImage.src = this.src;
			imageModal.style.display = 'flex';
		});
	});

	closeModal.addEventListener('click', function () {
		imageModal.style.display = 'none';
		modalImage.src = '';
	});

	imageModal.addEventListener('click', function (e) {
		if (e.target === imageModal) {
			imageModal.style.display = 'none';
			modalImage.src = '';
		}
	});

	startCamera();
</script>