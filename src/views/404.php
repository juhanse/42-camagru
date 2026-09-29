<?php $title = '404 - Page Introuvable'; ?>

<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.3/dist/dotlottie-wc.js" type="module"></script>

<style>
	.error-container {
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		min-height: 70vh;
		color: #e94560;
		text-align: center;
	}

	.error-container h1 {
		font-size: 4rem;
		margin-bottom: 0;
	}

	.error-container p {
		font-size: 1.2rem;
		color: #a2a2bd;
		margin-top: 10px;
		margin-bottom: 30px;
	}

	.error-container a.btn {
		background-color: #e94560;
		color: #ffffff;
		text-decoration: none;
		padding: 12px 24px;
		border-radius: 25px;
		font-weight: bold;
		font-size: 1rem;
		transition: background-color 0.3s ease, transform 0.2s ease;
	}

	.error-container a.btn:hover {
		background-color: #ff5270;
		transform: translateY(-2px);
	}

	.lottie-container {
		width: 300px;
		height: 300px;
		margin-bottom: 20px;
	}
</style>

<div class="error-container">
	<div class="lottie-container">
		<dotlottie-wc src="https://lottie.host/36ae7f02-f30c-4df4-8cb9-471aabdb1259/FE18x2DUNy.lottie"
			style="width: 300px;height: 300px" autoplay loop></dotlottie-wc>
	</div>

	<h1>Oups ! 404</h1>
	<p>Cette page n'existe pas</p>

	<a href="/" class="btn">Retour à l'accueil</a>
</div>