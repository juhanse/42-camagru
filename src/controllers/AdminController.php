<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Image.php';

class AdminController extends Controller
{
	public function __construct()
	{
		if (empty($_SESSION['user_id'])) {
			header("Location: /login");
			exit;
		}

		$userModel = new User();
		$user = $userModel->getUserById($_SESSION['user_id']);

		if (!$user || empty($user['is_admin'])) {
			header("Location: /");
			exit;
		}
	}

	public function index()
	{
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}

		$reportModel = new Report();
		$reportedImages = $reportModel->getReportedImages();

		$this->render('admin', [
			'title' => 'Administration - Camagru',
			'reportedImages' => $reportedImages
		]);
	}

	public function ignore()
	{
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$imageId = $_POST['image_id'] ?? null;
		if ($imageId) {
			$reportModel = new Report();
			$reportModel->clearReports($imageId);
		}

		header("Location: /admin");
		exit;
	}

	public function delete()
	{
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$imageId = $_POST['image_id'] ?? null;
		if ($imageId) {
			$imageModel = new Image();
			$imageModel->deleteImageAsAdmin($imageId);
		}

		header("Location: /admin");
		exit;
	}
}
