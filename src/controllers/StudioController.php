<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Image.php';

class StudioController extends Controller
{
	public function __construct()
	{
		if (empty($_SESSION['user_id'])) {
			header("Location: /login");
			exit;
		}
	}

	public function index()
	{
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		$filters = [];
		$filterDir = __DIR__ . '/../public/filters';

		if (is_dir($filterDir)) {
			$files = scandir($filterDir);
			foreach ($files as $file) {
				if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'png') {
					$filters[] = "/public/filters/{$file}";
				}
			}
		}

		$imageModel = new Image();
		$userImages = $imageModel->getUserImages($_SESSION['user_id']);

		$this->render('studio', [
			'title' => 'Studio - Camagru',
			'filters' => $filters,
			'userImages' => $userImages
		]);
	}

	public function save()
	{
		header('Content-Type: application/json');
		$data = json_decode(file_get_contents('php://input'), true);

		if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
			echo json_encode(['error' => 'Erreur CSRF']);
			return;
		}

		$imageBase64 = $data['image'] ?? '';
		$filterUrl = $data['filter'] ?? '';

		if (empty($imageBase64) || empty($filterUrl)) {
			echo json_encode(['error' => 'Données manquantes']);
			return;
		}

		$imageParts = explode(";base64,", $imageBase64);
		if (count($imageParts) !== 2) {
			echo json_encode(['error' => 'Format d\'image invalide']);
			return;
		}

		$base64Data = base64_decode($imageParts[1]);
		$sourceImg = imagecreatefromstring($base64Data);

		if (!$sourceImg) {
			echo json_encode(['error' => 'Impossible de traiter l\'image source']);
			return;
		}

		$targetW = 640;
		$targetH = 480;
		$finalImg = imagecreatetruecolor($targetW, $targetH);

		imagealphablending($finalImg, true);
		imagesavealpha($finalImg, true);
		$bgColor = imagecolorallocatealpha($finalImg, 255, 255, 255, 127);
		imagefill($finalImg, 0, 0, $bgColor);

		$srcW = imagesx($sourceImg);
		$srcH = imagesy($sourceImg);
		$srcRatio = $srcW / $srcH;
		$targetRatio = $targetW / $targetH;

		if ($srcRatio > $targetRatio) {
			$cropW = (int) ($srcH * $targetRatio);
			$cropH = $srcH;
			$cropX = (int) (($srcW - $cropW) / 2);
			$cropY = 0;
		} else {
			$cropW = $srcW;
			$cropH = (int) ($srcW / $targetRatio);
			$cropX = 0;
			$cropY = (int) (($srcH - $cropH) / 2);
		}

		imagecopyresampled($finalImg, $sourceImg, 0, 0, $cropX, $cropY, $targetW, $targetH, $cropW, $cropH);

		$filterPath = __DIR__ . '/..' . parse_url($filterUrl, PHP_URL_PATH);
		if (file_exists($filterPath)) {
			$filterImg = imagecreatefrompng($filterPath);
			if ($filterImg) {
				$filtW = imagesx($filterImg);
				$filtH = imagesy($filterImg);
				imagecopyresampled($finalImg, $filterImg, 0, 0, 0, 0, $targetW, $targetH, $filtW, $filtH);
			}
		}

		$uploadDir = __DIR__ . '/../public/uploads';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}

		$fileName = uniqid() . '.png';
		$uploadPath = "{$uploadDir}/{$fileName}";
		$publicPath = "/public/uploads/{$fileName}";

		imagepng($finalImg, $uploadPath);

		$imageModel = new Image();
		$imageId = $imageModel->create($_SESSION['user_id'], $publicPath);

		if ($imageId) {
			echo json_encode(['success' => true, 'image_url' => $publicPath]);
		} else {
			echo json_encode(['error' => 'Erreur lors de la sauvegarde en base de données']);
		}
	}

	public function delete()
	{
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$imageId = $_POST['image_id'] ?? null;
		if ($imageId) {
			$imageModel = new Image();
			$imageModel->deleteImage($imageId, $_SESSION['user_id']);
		}

		header("Location: /studio");
		exit;
	}
}