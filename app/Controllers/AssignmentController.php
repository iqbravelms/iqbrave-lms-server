<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class AssignmentController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }

    public function index() {}
    public function getAssignment($id)
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

                // Prepare statement to fetch assignments and their related files
                $stmt = $this->db->prepare("
                    SELECT a.*, af.*
                    FROM assignments a
                    LEFT JOIN assignment_files af ON a.id = af.AssignmentId
                    WHERE a.LessonId = ?
                ");
                $stmt->execute([$id]);
                $assignmentData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($assignmentData) {
                    $assignments = [];

                    foreach ($assignmentData as $row) {
                        $assignmentId = $row['id'];

                        // Check if this assignment is already in the array
                        if (!isset($assignments[$assignmentId])) {
                            $assignments[$assignmentId] = [
                                'assignment_id' => $assignmentId,
                                'structure' => $row['structure'],
                                'files' => []
                            ];
                        }

                        // Add file details if available
                        if ($row['AssignmentId'] !== null) {
                            $assignments[$assignmentId]['files'][] = [
                                'file_id' => $row['id'], // Assignment file ID
                                'AssignmentNo' => $row['AssignmentNo'],
                                'AssignmentName' => $row['AssignmentName'],
                                'Link' => $row['Link']
                            ];
                        }
                    }

                    // Reformat to return an indexed array
                    $assignments = array_values($assignments);

                    // Return the assignments data
                    echo json_encode(['status' => 'success', 'assignments' => $assignments]);
                } else {
                    // No assignment found
                    echo json_encode(['status' => 'error', 'message' => 'No assignments found for this Lesson ID.']);
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
