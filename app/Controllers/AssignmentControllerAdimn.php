<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class AssignmentControllerAdimn
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }
    public function getAssignment($id)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        $secret_key = $_ENV['SECRET_KEY'];
        if (!$secret_key) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Secret key is missing from the environment file.']);
            return;
        }

        $headers = getallheaders();
        $authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($headers['authorization']) ? $headers['authorization'] : null);

        if ($authorizationHeader) {
            $jwt = str_replace('Bearer ', '', $authorizationHeader);
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                if ($decoded->data->role === 'admin') {
                    try {
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
                    } catch (\PDOException $e) {

                        http_response_code(500);
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {

                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Access denied: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'No token provided']);
        }
    }
    public function updateAssignmentAndStructure()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        $secret_key = $_ENV['SECRET_KEY'];
        if (!$secret_key) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Secret key is missing from the environment file.']);
            return;
        }

        $headers = getallheaders();
        $authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($headers['authorization']) ? $headers['authorization'] : null);

        if ($authorizationHeader) {
            $jwt = str_replace('Bearer ', '', $authorizationHeader);
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                if ($decoded->data->role === 'admin') {
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Collect and trim form data
                        $assignmentId = trim($_POST['assignmentId']);
                        $assignmentNo = trim($_POST['assignmentNo']);
                        $assignmentName = trim($_POST['assignmentName']);
                        $assignmentFileId = trim($_POST['assignmentFileId']);
                        $assignmentLink = trim($_POST['assignmentLink']);
                        $assignmentStructure = trim($_POST['assignmentStructure']);

                        // Validate required fields
                        $errors = [];
                        if (empty($assignmentId)) $errors[] = 'assignmentId is required.';
                        if (empty($assignmentNo)) $errors[] = 'assignmentNo is required.';
                        if (empty($assignmentName)) $errors[] = 'assignmentName is required.';
                        if (empty($assignmentFileId)) $errors[] = 'assignmentFileId is required.';
                        if (empty($assignmentLink)) $errors[] = 'assignmentLink is required.';
                        if (empty($assignmentStructure)) $errors[] = 'assignmentStructure is required.';

                        // Return errors if validation fails
                        if (!empty($errors)) {
                            http_response_code(400);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'errors' => $errors]);
                            return;
                        }

                        // Proceed to update if validation passes
                        try {
                            $this->db->beginTransaction();

                            // Update the assignments table
                            $sql = "UPDATE assignments SET structure = :structure WHERE id = :assignmentId";
                            $stmt = $this->db->prepare($sql);
                            $stmt->bindParam(':structure', $assignmentStructure);
                            $stmt->bindParam(':assignmentId', $assignmentId);
                            $stmt->execute();

                            // Update the assignment_files table
                            $sql = "UPDATE assignment_files SET AssignmentNo = :assignmentNo, AssignmentName = :assignmentName, Link = :assignmentLink WHERE id = :assignmentFileId";
                            $stmt = $this->db->prepare($sql);
                            $stmt->bindParam(':assignmentNo', $assignmentNo);
                            $stmt->bindParam(':assignmentName', $assignmentName);
                            $stmt->bindParam(':assignmentLink', $assignmentLink);
                            $stmt->bindParam(':assignmentFileId', $assignmentFileId);
                            $stmt->execute();

                            $this->db->commit();

                            echo json_encode(['success' => true, 'message' => 'Assignment updated successfully.']);
                            return;
                        } catch (\PDOException $e) {
                            $this->db->rollBack();
                            http_response_code(500);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                            return;
                        }
                    } else {
                        http_response_code(405); // Method not allowed
                        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
                        return;
                    }
                } else {
                    http_response_code(403); // Forbidden
                    echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
                }
            } catch (\Exception $e) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['message' => 'Access denied: ' . $e->getMessage()]);
            }
        } else {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'No token provided']);
        }
    }
}
