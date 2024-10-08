<?php

namespace App\Controllers;
use PDOException;

use PDO;

class ModuleController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }

    public function index($id)
    {
        // Use a prepared statement to prevent SQL injection
        try{
            $stmt = $this->db->prepare("SELECT * FROM modules WHERE CourseId = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Fetch all records that match the CourseId
            $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Check if any modules were found
            if ($modules && count($modules) > 0) {
                // Return all modules as an array
                echo json_encode(['status' => 'success', 'modules' => $modules]);
            } else {
                // Return an error message if no modules were found
                echo json_encode(['status' => 'error', 'message' => 'Modules not found.']);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database query failed: ' . $e->getMessage()
            ]);
            return;
        }
        
    }
}
