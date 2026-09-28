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
			header("Location: /login");
			exit;
		}
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$imageId = $_POST['image_id'] ?? null;
		if ($imageId) {
			$likeModel = new Like();
			$likeModel->toggleLike($_SESSION['user_id'], $imageId);
		}

		header("Location: /" . (isset($_POST['page']) ? "?page=" . (int) $_POST['page'] : ""));
		exit;
	}

	public function comment()
	{
		if (empty($_SESSION['user_id'])) {
			header("Location: /login");
			exit;
		}
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$imageId = $_POST['image_id'] ?? null;
		$content = trim($_POST['content'] ?? '');

		if ($imageId && !empty($content)) {
			$commentModel = new Comment();
			$commentModel->addComment($_SESSION['user_id'], $imageId, $content);

			$imageModel = new Image();
			$image = $imageModel->getImageById($imageId);

			if ($image && $image['notify_comments'] && $image['user_id'] != $_SESSION['user_id']) {
				$subject = "Camagru - Nouveau commentaire sur votre image";
				$message = "Bonjour " . $image['username'] . ",\r\n\r\nVotre image a recu un nouveau commentaire de " . $_SESSION['username'] . " :\r\n\"" . $content . "\"\r\n\r\nA bientot sur Camagru !";
				$headers = "From: no-reply@camagru.com\r\n";
				$headers .= "Reply-To: no-reply@camagru.com\r\n";
				$headers .= "X-Mailer: PHP/" . phpversion();

				mail($image['email'], $subject, $message, $headers);
			}
		}

		header("Location: /" . (isset($_POST['page']) ? "?page=" . (int) $_POST['page'] : ""));
		exit;
	}
}
