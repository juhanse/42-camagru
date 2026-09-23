<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{

	public function register()
	{
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
		$this->render('register', ['title' => 'Inscription - Camagru']);
	}

	public function registerPost()
	{
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$username = trim($_POST['username'] ?? '');
		$email = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';
		$errors = [];

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "L'adresse email n'est pas valide.";
		}

		if (strlen($username) < 3 || strlen($username) > 50 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
			$errors[] = "Le nom d'utilisateur doit contenir entre 3 et 50 caractères alphanumériques.";
		}

		$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/';
		if (!preg_match($passwordPattern, $password)) {
			$errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
		}

		$userModel = new User();

		if (empty($errors)) {
			if ($userModel->userExists($username, $email)) {
				$errors[] = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
			} else {
				$token = bin2hex(random_bytes(50));

				if ($userModel->create($username, $email, $password, $token)) {
					$verifyLink = "http://" . $_SERVER['HTTP_HOST'] . "/verify?token=" . $token;
					$subject = "Camagru - Confirmez votre inscription";
					$message = "Bonjour $username,\r\n\r\nMerci de vous etre inscrit sur Camagru. Cliquez sur le lien suivant pour activer votre compte :\r\n$verifyLink\r\n\r\nA bientot !";
					$headers = "From: no-reply@camagru.com\r\n";
					$headers .= "Reply-To: no-reply@camagru.com\r\n";
					$headers .= "X-Mailer: PHP/" . phpversion();

					mail($email, $subject, $message, $headers);

					$this->render('register', [
						'title' => 'Inscription - Camagru',
						'success' => "Inscription réussie ! Veuillez vérifier vos emails pour activer votre compte."
					]);
					return;
				}
			}
		}

		$this->render('register', [
			'title' => 'Inscription - Camagru',
			'errors' => $errors,
			'old_username' => htmlspecialchars($username),
			'old_email' => htmlspecialchars($email)
		]);
	}

	public function verify()
	{
		$token = $_GET['token'] ?? '';

		if (empty($token)) {
			$this->render('verify', ['title' => 'Vérification', 'message' => 'Lien invalide.']);
			return;
		}

		$userModel = new User();
		if ($userModel->verifyToken($token)) {
			$this->render('verify', ['title' => 'Vérification', 'message' => 'Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.']);
		} else {
			$this->render('verify', ['title' => 'Vérification', 'message' => 'Lien invalide ou compte déjà vérifié.']);
		}
	}

	public function login()
	{
		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
		$this->render('login', ['title' => 'Connexion - Camagru']);
	}

	public function loginPost()
	{
		if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			die("Erreur CSRF");
		}

		$username = trim($_POST['username'] ?? '');
		$password = $_POST['password'] ?? '';
		$error = "";

		$userModel = new User();
		$user = $userModel->getUserByUsername($username);

		if ($user && password_verify($password, $user['password'])) {
			if ($user['is_verified']) {
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['username'] = $user['username'];
				header("Location: /");
				exit;
			} else {
				$error = "Veuillez vérifier votre adresse email avant de vous connecter.";
			}
		} else {
			$error = "Nom d'utilisateur ou mot de passe incorrect.";
		}

		$this->render('login', [
			'title' => 'Connexion - Camagru',
			'error' => $error,
			'old_username' => htmlspecialchars($username)
		]);
	}

	public function logout()
	{
		session_unset();
		session_destroy();
		header("Location: /");
		exit;
	}
}
