<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController extends Controller
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

		$userModel = new User();
		$user = $userModel->getUserById($_SESSION['user_id']);

		$this->render('profile', [
			'title' => 'Profil - Camagru',
			'user' => $user
		]);
	}

	public function update()
	{
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$userModel = new User();
		$user = $userModel->getUserById($_SESSION['user_id']);

		$username = trim($_POST['username'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$notify = isset($_POST['notify_comments']) ? 1 : 0;

		$password = $_POST['password'] ?? '';
		$password_confirm = $_POST['password_confirm'] ?? '';

		$errors = [];
		$success = "";

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "L'adresse email n'est pas valide.";
		}

		if (strlen($username) < 3 || strlen($username) > 50 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
			$errors[] = "Le nom d'utilisateur doit contenir entre 3 et 50 caractères alphanumériques.";
		}

		if ($userModel->checkOtherUserExists($username, $email, $_SESSION['user_id'])) {
			$errors[] = "Ce nom d'utilisateur ou cet email est déjà utilisé par un autre compte.";
		}

		if (!empty($password)) {
			if ($password !== $password_confirm) {
				$errors[] = "Les mots de passe ne correspondent pas.";
			} else {
				$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
				if (!preg_match($passwordPattern, $password)) {
					$errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
				}
			}
		}

		if (empty($errors)) {
			$userModel->updateProfile($_SESSION['user_id'], $username, $email, $notify);

			if (!empty($password)) {
				$userModel->updatePasswordOnly($_SESSION['user_id'], $password);
			}

			$_SESSION['username'] = $username;
			$success = "Profil mis à jour avec succès.";
			$user = $userModel->getUserById($_SESSION['user_id']);
		}

		$this->render('profile', [
			'title' => 'Profil - Camagru',
			'user' => $user,
			'errors' => $errors,
			'success' => $success
		]);
	}
}