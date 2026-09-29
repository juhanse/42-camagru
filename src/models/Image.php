<?php
require_once __DIR__ . '/../core/Database.php';

class Image
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function getTotalCount()
	{
		$stmt = $this->db->query("SELECT COUNT(*) as total FROM images");
		return $stmt->fetch()['total'];
	}

	public function getPaginated($limit, $offset)
	{
		$stmt = $this->db->prepare("SELECT images.*, users.username, users.notify_comments, users.email FROM images JOIN users ON images.user_id = users.id ORDER BY images.created_at DESC LIMIT :limit OFFSET :offset");
		$stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
		$stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getImageById($id)
	{
		$stmt = $this->db->prepare("SELECT images.*, users.username, users.notify_comments, users.email FROM images JOIN users ON images.user_id = users.id WHERE images.id = :id");
		$stmt->execute(['id' => $id]);
		return $stmt->fetch();
	}

	public function create($userId, $filePath)
	{
		$stmt = $this->db->prepare("INSERT INTO images (user_id, file_path) VALUES (:user_id, :file_path)");
		$stmt->execute(['user_id' => $userId, 'file_path' => $filePath]);
		return $this->db->lastInsertId();
	}

	public function getUserImages($userId)
	{
		$stmt = $this->db->prepare("SELECT * FROM images WHERE user_id = :user_id ORDER BY created_at DESC");
		$stmt->execute(['user_id' => $userId]);
		return $stmt->fetchAll();
	}

	public function deleteImage($imageId, $userId)
	{
		$stmt = $this->db->prepare("SELECT file_path FROM images WHERE id = :id AND user_id = :user_id");
		$stmt->execute(['id' => $imageId, 'user_id' => $userId]);
		$image = $stmt->fetch();

		if ($image) {
			$filePath = __DIR__ . '/..' . $image['file_path'];
			if (file_exists($filePath)) {
				unlink($filePath);
			}
			$delStmt = $this->db->prepare("DELETE FROM images WHERE id = :id AND user_id = :user_id");
			return $delStmt->execute(['id' => $imageId, 'user_id' => $userId]);
		}
		return false;
	}

	public function deleteImageAsAdmin($imageId)
	{
		$stmt = $this->db->prepare("SELECT file_path FROM images WHERE id = :id");
		$stmt->execute(['id' => $imageId]);
		$image = $stmt->fetch();

		if ($image) {
			$filePath = __DIR__ . '/..' . $image['file_path'];
			if (file_exists($filePath)) {
				unlink($filePath);
			}
			$delStmt = $this->db->prepare("DELETE FROM images WHERE id = :id");
			return $delStmt->execute(['id' => $imageId]);
		}
		return false;
	}
}
