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

	public function getUserByEmail($email)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
		$stmt->execute(['email' => $email]);
		return $stmt->fetch();
	}

	public function setToken($userId, $token)
	{
		$stmt = $this->db->prepare("UPDATE users SET token = :token WHERE id = :id");
		return $stmt->execute(['token' => $token, 'id' => $userId]);
	}

	public function getUserByToken($token)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE token = :token AND token IS NOT NULL");
		$stmt->execute(['token' => $token]);
		return $stmt->fetch();
	}

	public function updatePassword($userId, $password)
	{
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$stmt = $this->db->prepare("UPDATE users SET password = :password, token = NULL WHERE id = :id");
		return $stmt->execute(['password' => $hash, 'id' => $userId]);
	}

	public function getUserById($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}

	public function checkOtherUserExists($username, $email, $userId)
	{
		$stmt = $this->db->prepare("SELECT id FROM users WHERE (username = :username OR email = :email) AND id != :id");
		$stmt->execute(['username' => $username, 'email' => $email, 'id' => $userId]);
		return $stmt->fetch();
	}

	public function updateProfile($id, $username, $email, $notify)
	{
		$stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email, notify_comments = :notify WHERE id = :id");
		return $stmt->execute([
			'username' => $username,
			'email' => $email,
			'notify' => $notify,
			'id' => $id
		]);
	}

	public function updatePasswordOnly($id, $password)
	{
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
		return $stmt->execute(['password' => $hash, 'id' => $id]);
	}

	public function getAllUsersWithImageCount()
	{
		$stmt = $this->db->query("SELECT u.id, u.username, u.email, COUNT(i.id) as image_count FROM users u LEFT JOIN images i ON u.id = i.user_id GROUP BY u.id ORDER BY u.created_at DESC");
		return $stmt->fetchAll();
	}
}