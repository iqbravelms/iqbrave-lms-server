<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class LessonController
{
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
    public function getLesson($id)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();
    
        // Access secret key from the .env file
        $secret_key = $_ENV['SECRET_KEY'];
    
        $headers = getallheaders();
    
        if (isset($headers['Authorization'])) {
            $jwt = str_replace('Bearer ', '', $headers['Authorization']); // Remove Bearer prefix
    
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    
                // Prepare the statement to fetch the lesson and its steps in one query
                $stmt = $this->db->prepare("
                    SELECT l.*, ls.* 
                    FROM lessons l 
                    LEFT JOIN lesson_steps ls ON l.id = ls.LessonId 
                    WHERE l.id = ?
                ");
                $stmt->execute([$id]);
                $lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if ($lessonData) {
                    $lessonId = $lessonData[0]['id']; // Assuming the lesson exists
                    $lesson = [
                        'id' => $lessonId,
                        'topic' => $lessonData[0]['topic'],
                        'link' => $lessonData[0]['link'],
                        'Note' => $lessonData[0]['Note'],
                        'steps' => [],
                    ];
    
                    // Iterate through the result to extract steps
                    foreach ($lessonData as $row) {
                        if ($row['LessonId'] !== null) { // Check if the step exists
                            $lesson['steps'][] = [
                                'step_id' => $row['id'], // Assuming `id` is the step ID in lesson_steps
                                'description' => $row['description'], // Change to the correct field name
                                // Add more fields as needed
                            ];
                        }
                    }
    
                    // Return the lesson data
                    echo json_encode(['status' => 'success', 'lesson' => $lesson]);
                } else {
                    // No lesson found
                    echo json_encode(['status' => 'error', 'message' => 'No lesson found for this ID.']);
                }
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(['message' => 'Access denied: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'No token provided']);
        }
    }
    
    
}
