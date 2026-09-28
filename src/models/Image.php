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
}
