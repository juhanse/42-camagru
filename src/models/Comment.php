<?php
require_once __DIR__ . '/../core/Database.php';

class Comment
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function addComment($userId, $imageId, $content)
	{
		$stmt = $this->db->prepare("INSERT INTO comments (user_id, image_id, content) VALUES (:user_id, :image_id, :content)");
		return $stmt->execute(['user_id' => $userId, 'image_id' => $imageId, 'content' => $content]);
	}

	public function getCommentsForImage($imageId)
	{
		$stmt = $this->db->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE image_id = :image_id ORDER BY created_at ASC");
		$stmt->execute(['image_id' => $imageId]);
		return $stmt->fetchAll();
	}
}
