<?php
namespace App\Controllers;

use PDO;

class LessonController {
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }

    public function index($id)
    {
        // Fetch the module based on ID (add your logic here)
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE ModuleId = ?");
        $stmt->execute([$id]);
        $module = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($module) {
            echo json_encode(['status' => 'success', 'module' => $module]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Module not found.']);
        }
    }
}
