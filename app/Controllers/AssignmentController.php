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

                // Ensure 'id' is present in the decoded JWT
                $studentId = isset($decoded->id) ? $decoded->id : (isset($decoded->data->id) ? $decoded->data->id : null);

                if (!$studentId) {
                    echo json_encode(['status' => 'error', 'message' => 'Student ID not found in token']);
                    return;
                }

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

                    // Now, execute the SQL query for student assignments
                    foreach ($assignments as &$assignment) {
                        $studentAssignmentsData = [];
                        foreach ($assignment['files'] as $file) {
                            // Check if the student assignment already exists
                            $checkStmt = $this->db->prepare("SELECT * FROM student_assignments WHERE StudentId = :studentId AND AssignmentFileId = :assignmentFileId");
                            $checkStmt->execute(['studentId' => $studentId, 'assignmentFileId' => $file['file_id']]);
                            $studentAssignments = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

                            // If no student assignments exist, insert data into student_assignments table
                            if (empty($studentAssignments) && $decoded->data->role === 'user') {
                                $insertStmt = $this->db->prepare("
                                    INSERT INTO student_assignments (AssignmentFileId, StudentId, StartDate, DueDate) 
                                    VALUES (:assignmentFileId, :studentId, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY))
                                ");
                                $insertStmt->execute([
                                    'assignmentFileId' => $file['file_id'],
                                    'studentId' => $studentId
                                ]);

                                // Optionally fetch the newly inserted student assignment for confirmation
                                $studentAssignmentsData[] = [
                                    'id' => $this->db->lastInsertId(),
                                    'AssignmentFileId' => $file['file_id'],
                                    'StudentId' => $studentId,
                                    'StartDate' => date('Y-m-d'),
                                    'DueDate' => date('Y-m-d', strtotime('+14 days'))
                                ];
                            } else {
                                // Add existing student assignments to the data
                                $studentAssignmentsData = array_merge($studentAssignmentsData, $studentAssignments);
                            }
                        }

                        // Assign all student assignments to the current assignment
                        $assignment['student_assignments'] = $studentAssignmentsData;
                    }

                    // Return the assignments data
                    echo json_encode(['status' => 'success', 'assignments' => $assignments, 'data' => $decoded]);
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
    // Assuming your route is set as $router->add('POST', '/api/submitassignment/{id}', 'AssignmentController@submitAssignment');

    public function submitAssignment()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        // Access secret key from the .env file
        $secret_key = $_ENV['SECRET_KEY'];

        // Get headers to check for Authorization token
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $jwt = str_replace('Bearer ', '', $headers['Authorization']); // Remove Bearer prefix

            try {
                // Decode JWT token
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                // Retrieve student ID from JWT payload
                $studentId = isset($decoded->id) ? $decoded->id : (isset($decoded->data->id) ? $decoded->data->id : null);

                if (!$studentId) {
                    echo json_encode(['status' => 'error', 'message' => 'Student ID not found in token']);
                    return;
                }

                // Handle POST request for assignment submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    // Get the assignmentId from form data or route parameter
                    $assignmentId = $_POST['assignmentId'] ?? null;
                    $driveLink = $_POST['driveLink'] ?? null;

                    // Check if all required fields are provided
                    if (!$assignmentId || !$driveLink) {
                        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
                        return;
                    }
                    if ($decoded->data->role === 'admin') {
                        echo json_encode(['status' => 'error', 'message' => 'You can not submit assignmnet']);
                        return;
                    }

                    // Prepare the SQL statement
                    $insertStmt = $this->db->prepare("UPDATE student_assignments SET link = :link, SubmitedDate = NOW() WHERE StudentId= :StudentId AND AssignmentFileId = :assignmentId");

                    // Bind the parameters
                    $insertStmt->bindParam(':StudentId', $studentId); // Use ':link' as a placeholder for the drive link
                    $insertStmt->bindParam(':link', $driveLink); // Use ':link' as a placeholder for the drive link
                    $insertStmt->bindParam(':assignmentId', $assignmentId); // Assuming you have the assignmentId available for the update

                    // Execute the prepared statement
                    if ($insertStmt->execute()) {
                        // Respond with success
                        echo json_encode([
                            'status' => 'success',
                            'studentId' => $studentId,
                            'assignmentId' => $assignmentId,
                            'driveLink' => $driveLink
                        ]);
                    } else {
                        // Handle execution failure
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update assignment']);
                    }
                } else {
                    // Method not allowed response
                    http_response_code(405); // Method Not Allowed
                    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
                }
            } catch (\Exception $e) {
                // Invalid token or decoding failure
                http_response_code(401); // Unauthorized
                echo json_encode(['message' => 'Access denied: ' . $e->getMessage()]);
            }
        } else {
            // No token provided
            http_response_code(401); // Unauthorized
            echo json_encode(['message' => 'No token provided']);
        }
    }
}
