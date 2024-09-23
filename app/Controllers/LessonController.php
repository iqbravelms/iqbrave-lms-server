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
        // Prepare the statement to fetch all lessons based on the ModuleId
        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE ModuleId = ?");
        $stmt->execute([$id]);
    
        // Fetch all lessons associated with the given ModuleId
        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Check if lessons were found
        if ($lessons && count($lessons) > 0) {
            // Return all lessons as an array in the JSON response
            echo json_encode(['status' => 'success', 'lessons' => $lessons]);
        } else {
            // Return an error if no lessons were found
            echo json_encode(['status' => 'error', 'message' => 'No lessons found for this module.']);
        }
    }
    
}
