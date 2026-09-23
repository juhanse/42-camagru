<?php
require_once __DIR__ . '/../core/Database.php';

class User
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function userExists($username, $email)
	{
		$stmt = $this->db->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
		$stmt->execute(['username' => $username, 'email' => $email]);
		return $stmt->fetch();
	}

	public function create($username, $email, $password, $token)
	{
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$stmt = $this->db->prepare("INSERT INTO users (username, email, password, token) VALUES (:username, :email, :password, :token)");
		return $stmt->execute([
			'username' => $username,
			'email' => $email,
			'password' => $hash,
			'token' => $token
		]);
	}

	public function verifyToken($token)
	{
		$stmt = $this->db->prepare("SELECT id FROM users WHERE token = :token AND is_verified = FALSE");
		$stmt->execute(['token' => $token]);
		$user = $stmt->fetch();

		if ($user) {
			$update = $this->db->prepare("UPDATE users SET is_verified = TRUE, token = NULL WHERE id = :id");
			return $update->execute(['id' => $user['id']]);
		}
		return false;
	}

	public function getUserByUsername($username)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->execute(['username' => $username]);
		return $stmt->fetch();
	}
}