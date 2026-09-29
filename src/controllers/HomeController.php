<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Image.php';
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../models/Comment.php';

class HomeController extends Controller
{
	public function index()
	{
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		$imageModel = new Image();
		$likeModel = new Like();
		$commentModel = new Comment();

		$limit = 5;
		$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		if ($page < 1)
			$page = 1;
		$offset = ($page - 1) * $limit;

		$totalImages = $imageModel->getTotalCount();
		$totalPages = ceil($totalImages / $limit);

		$images = $imageModel->getPaginated($limit, $offset);

		$gallery = [];
		$userId = $_SESSION['user_id'] ?? null;

		foreach ($images as $img) {
			$img['likes_count'] = $likeModel->getLikesCount($img['id']);
			$img['user_liked'] = $userId ? $likeModel->hasLiked($userId, $img['id']) : false;
			$img['comments'] = $commentModel->getCommentsForImage($img['id']);
			$gallery[] = $img;
		}

		$this->render('home', [
			'title' => 'Galerie - Camagru',
			'gallery' => $gallery,
			'page' => $page,
			'totalPages' => $totalPages
		]);
	}

	public function like()
	{
		if (empty($_SESSION['user_id'])) {
			echo json_encode(['error' => 'Non autorisé']);
			exit;
		}

		$data = json_decode(file_get_contents('php://input'), true);

		if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
			echo json_encode(['error' => 'Erreur CSRF']);
			exit;
		}

		$imageId = $data['image_id'] ?? null;
		if ($imageId) {
			$likeModel = new Like();
			$likeModel->toggleLike($_SESSION['user_id'], $imageId);

			$newCount = $likeModel->getLikesCount($imageId);
			$userLiked = $likeModel->hasLiked($_SESSION['user_id'], $imageId);

			echo json_encode([
				'success' => true,
				'likes_count' => $newCount,
				'user_liked' => $userLiked
			]);
			exit;
		}

		echo json_encode(['error' => 'Requête invalide']);
		exit;
	}

	public function comment()
	{
		if (empty($_SESSION['user_id'])) {
			echo json_encode(['error' => 'Non autorisé']);
			exit;
		}

		$data = json_decode(file_get_contents('php://input'), true);

		if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
			echo json_encode(['error' => 'Erreur CSRF']);
			exit;
		}

		$imageId = $data['image_id'] ?? null;
		$content = trim($data['content'] ?? '');

		if (mb_strlen($content) > 255) {
			$content = mb_substr($content, 0, 255);
		}

		if ($imageId && !empty($content)) {
			$commentModel = new Comment();
			$commentModel->addComment($_SESSION['user_id'], $imageId, $content);

			$imageModel = new Image();
			$image = $imageModel->getImageById($imageId);

			if ($image && !empty($image['notify_comments']) && $image['user_id'] != $_SESSION['user_id']) {
				$subject = "Camagru - Nouveau commentaire";
				$message = "Bonjour " . $image['username'] . ",\r\n\r\nVotre image a recu un nouveau commentaire de " . $_SESSION['username'] . " :\r\n\"" . $content . "\"\r\n\r\nA bientot sur Camagru !";
				$headers = "From: no-reply@camagru.com\r\n";
				$headers .= "Reply-To: no-reply@camagru.com\r\n";
				$headers .= "X-Mailer: PHP/" . phpversion();

				mail($image['email'], $subject, $message, $headers);
			}

			echo json_encode([
				'success' => true,
				'username' => $_SESSION['username'],
				'content' => htmlspecialchars($content)
			]);
			exit;
		}

		echo json_encode(['error' => 'Requête invalide']);
		exit;
	}

	public function report()
	{
		if (empty($_SESSION['user_id'])) {
			echo json_encode(['error' => 'Non autorisé']);
			exit;
		}

		$data = json_decode(file_get_contents('php://input'), true);

		if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
			echo json_encode(['error' => 'Erreur CSRF']);
			exit;
		}

		$imageId = $data['image_id'] ?? null;
		if ($imageId) {
			$reportModel = new Report();
			$reportModel->addReport($_SESSION['user_id'], $imageId);
			echo json_encode(['success' => true]);
			exit;
		}

		echo json_encode(['error' => 'Requête invalide']);
		exit;
	}
}
