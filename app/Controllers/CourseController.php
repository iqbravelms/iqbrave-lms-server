<?php
namespace App\Controllers;

use PDO;
use PDOException;

class CourseController {
    private $db;

    public function __construct()
    {
        // Include the database connection
        require_once __DIR__ . '/../config/database.php';  // Ensure the correct path to db.php
        $this->db = $db;  // Assign the $db variable from db.php
    }

    public function index()
    {
        // Prepare and execute the query
        try {
            $stmt = $this->db->query("SELECT * FROM courses");
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database query failed: ' . $e->getMessage()
            ]);
            return;
        }

        // Return the courses as a JSON response
        echo json_encode([
            'status' => 'success',
            'courses' => $courses
        ]);
    }
}
