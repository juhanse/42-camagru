<?php
require_once __DIR__ . '/../core/Database.php';

class Report
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getInstance();
	}

	public function addReport($userId, $imageId)
	{
		$stmt = $this->db->prepare("SELECT id FROM reports WHERE user_id = :user_id AND image_id = :image_id");
		$stmt->execute(['user_id' => $userId, 'image_id' => $imageId]);

		if (!$stmt->fetch()) {
			$ins = $this->db->prepare("INSERT INTO reports (user_id, image_id) VALUES (:user_id, :image_id)");
			return $ins->execute(['user_id' => $userId, 'image_id' => $imageId]);
		}
		return false;
	}

	public function getReportedImages()
	{
		$stmt = $this->db->query("SELECT images.id, images.file_path, images.created_at, users.username, COUNT(reports.id) as report_count FROM images JOIN reports ON images.id = reports.image_id JOIN users ON images.user_id = users.id GROUP BY images.id ORDER BY report_count DESC");
		return $stmt->fetchAll();
	}

	public function clearReports($imageId)
	{
		$stmt = $this->db->prepare("DELETE FROM reports WHERE image_id = :image_id");
		return $stmt->execute(['image_id' => $imageId]);
	}

	public function hasReported($userId, $imageId)
	{
		$stmt = $this->db->prepare("SELECT id FROM reports WHERE user_id = :user_id AND image_id = :image_id");
		$stmt->execute(['user_id' => $userId, 'image_id' => $imageId]);
		return (bool) $stmt->fetch();
	}
}
