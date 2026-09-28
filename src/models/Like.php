<?php
require_once __DIR__ . '/../core/Database.php';

class Like
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function toggleLike($userId, $imageId)
	{
		$stmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = :user_id AND image_id = :image_id");
		$stmt->execute(['user_id' => $userId, 'image_id' => $imageId]);

		if ($stmt->fetch()) {
			$del = $this->db->prepare("DELETE FROM likes WHERE user_id = :user_id AND image_id = :image_id");
			$del->execute(['user_id' => $userId, 'image_id' => $imageId]);
			return false;
		} else {
			$ins = $this->db->prepare("INSERT INTO likes (user_id, image_id) VALUES (:user_id, :image_id)");
			$ins->execute(['user_id' => $userId, 'image_id' => $imageId]);
			return true;
		}
	}

	public function getLikesCount($imageId)
	{
		$stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes WHERE image_id = :image_id");
		$stmt->execute(['image_id' => $imageId]);
		return $stmt->fetch()['count'];
	}

	public function hasLiked($userId, $imageId)
	{
		$stmt = $this->db->prepare("SELECT id FROM likes WHERE user_id = :user_id AND image_id = :image_id");
		$stmt->execute(['user_id' => $userId, 'image_id' => $imageId]);
		return (bool) $stmt->fetch();
	}
}
