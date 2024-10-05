<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class AddLessonController
{
    private $db;

    public function __construct()
    {
        // Include the database connection
        require_once __DIR__ . '/../config/database.php';  // Ensure the correct path to db.php
        $this->db = $db;  // Assign the $db variable from db.php
    }

    public function getAllCorse()
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
                        $stmt = $this->db->prepare("SELECT * FROM courses");
                        $stmt->execute();
                        $course = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($course && count($course) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'courses' => $course]);
                        } else {
                            http_response_code(404);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'No users found for this module.']);
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
    public function getLesson($id)
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
                        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE ModuleId='$id'");
                        $stmt->execute();
                        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($lessons && count($lessons) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'lessons' => $lessons]);
                        } else {
                            http_response_code(404);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'No users found for this module.']);
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
    public function getSteps($id)
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
                        $stmt = $this->db->prepare("SELECT * FROM lesson_steps WHERE LessonId='$id'");
                        $stmt->execute();
                        $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($steps && count($steps) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'steps' => $steps]);
                        } else {
                            http_response_code(404);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'No users found for this module.']);
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
    public function updateLesson()
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
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Collect form data
                            // Trim and collect form data
                            $lessonId = trim($_POST['lessonId']);
                            $moduleId = trim($_POST['moduleId']);
                            $topic = trim($_POST['topic']);
                            $link = trim($_POST['link']);
                            $note = trim($_POST['note']);

                            // Initialize an empty errors array
                            $errors = [];
                            if (empty($lessonId)) {
                                $errors[] = 'Lesson ID is required.';
                            }
                            if (empty($moduleId)) {
                                $errors[] = 'Module ID is required.';
                            }
                            if (empty($topic)) {
                                $errors[] = 'Topic is required.';
                            }
                            if (empty($link)) {
                                $errors[] = 'Link is required.';
                            }
                            if (empty($note)) {
                                $errors[] = 'Note is required.';
                            }


                            // Return errors if any exist
                            if (!empty($errors)) {
                                http_response_code(400);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'errors' => $errors]);
                                return;
                            }


                            // Proceed to update the user in the database if validation passes
                            try {

                                // Prepare the update statement
                                $sql = "UPDATE lessons SET 
                                            topic = :topic,link = :link,Note=:Note WHERE id = :id";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':topic', $topic);
                                $stmt->bindParam(':link', $link);
                                $stmt->bindParam(':Note', $note);
                                $stmt->bindParam(':id', $lessonId);

                                // Execute the statement
                                $stmt->execute();

                                echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
                                return;
                            } catch (\PDOException $e) {
                                http_response_code(500);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                            }
                        } else {
                            echo json_encode(['error' => 'Invalid request method.']);
                            return;
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
    public function updateStep()
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
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Collect form data
                            // Trim and collect form data
                            $stepId = trim($_POST['stepId']);
                            $LessonId = trim($_POST['LessonId']);
                            $stepNo = trim($_POST['stepNo']);
                            $stepDescription = trim($_POST['stepDescription']);

                            // Initialize an empty errors array
                            $errors = [];
                            if (empty($stepId)) {
                                $errors[] = 'Step ID is required.';
                            }
                            if (empty($LessonId)) {
                                $errors[] = 'Lesson ID is required.';
                            }
                            if (empty($stepNo)) {
                                $errors[] = 'stepNo is required.';
                            }
                            if (empty($stepDescription)) {
                                $errors[] = 'Description is required.';
                            }


                            // Return errors if any exist
                            if (!empty($errors)) {
                                http_response_code(400);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'errors' => $errors]);
                                return;
                            }


                            // Proceed to update the user in the database if validation passes
                            try {

                                // Prepare the update statement
                                $sql = "UPDATE lesson_steps SET StepNo = :stepNo,description = :stepDescription WHERE id = :id";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':stepNo', $stepNo);
                                $stmt->bindParam(':stepDescription', $stepDescription);
                                $stmt->bindParam(':id', $stepId);

                                // Execute the statement
                                $stmt->execute();

                                echo json_encode(['success' => true, 'message' => 'Step updated successfully.']);
                                return;
                            } catch (\PDOException $e) {
                                http_response_code(500);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                            }
                        } else {
                            echo json_encode(['error' => 'Invalid request method.']);
                            return;
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

    public function addLesson()
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
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $topicName = isset($_POST['topicName']) ? trim($_POST['topicName']) : null;
                            $linkName = isset($_POST['linkName']) ? trim($_POST['linkName']) : null;
                            $noteName = isset($_POST['noteName']) ? trim($_POST['noteName']) : null;
                            $moduleId = isset($_POST['moduleId']) ? intval($_POST['moduleId']) : null; // Collect ModuleId
                            $structure = isset($_POST['structure']) ? trim($_POST['structure']) : null;
                            $assignmentNo1 = isset($_POST['assignmentNo1']) ? trim($_POST['assignmentNo1']) : null;
                            $assignmentName1 = isset($_POST['assignmentName1']) ? trim($_POST['assignmentName1']) : null;
                            $Link1 = isset($_POST['Link1']) ? trim($_POST['Link1']) : null;
                            $assignmentNo2 = isset($_POST['assignmentNo2']) ? trim($_POST['assignmentNo2']) : null;
                            $assignmentName2 = isset($_POST['assignmentName2']) ? trim($_POST['assignmentName2']) : null;
                            $Link2 = isset($_POST['Link2']) ? trim($_POST['Link2']) : null;
                            $assignmentNo3 = isset($_POST['assignmentNo3']) ? trim($_POST['assignmentNo3']) : null;
                            $assignmentName3 = isset($_POST['assignmentName3']) ? trim($_POST['assignmentName3']) : null;
                            $Link3 = isset($_POST['Link3']) ? trim($_POST['Link3']) : null;

                            $steps = isset($_POST['steps']) ? json_decode($_POST['steps'], true) : [];
                            if (!$topicName || !$linkName || !$noteName || !$moduleId || !$assignmentNo1 || !$assignmentNo2 || !$assignmentNo3 || !$assignmentName1 || !$assignmentName2 || !$assignmentName3 || !$Link1 || !$Link2 || !$Link3 || !$structure) {
                                echo json_encode(['error' => 'Missing required fields.']);
                                exit();
                            }
                            try {
                                // Begin transaction
                                $this->db->beginTransaction();
                                $lessonSql = "INSERT INTO lessons (ModuleId, topic, link, Note) VALUES (:moduleId, :topic, :link, :note)";
                                $stmt = $this->db->prepare($lessonSql);
                                $stmt->execute([
                                    ':moduleId' => $moduleId,
                                    ':topic' => $topicName,
                                    ':link' => $linkName,
                                    ':note' => $noteName,
                                ]);
                                $lessonId = $this->db->lastInsertId();
                                $stepSql = "INSERT INTO lesson_steps (LessonId, StepNo, description) VALUES (:lessonId, :stepNo, :description)";
                                $stmt = $this->db->prepare($stepSql);
                                foreach ($steps as $index => $step) {
                                    $stmt->execute([
                                        ':lessonId' => $lessonId,
                                        ':stepNo' => $index + 1, // Step numbers start from 1
                                        ':description' => $step['description'],
                                    ]);
                                }
                                $assignmentSql = "INSERT INTO assignments (LessonId, structure) VALUES (:lessonId, :structure)";
                                $stmt = $this->db->prepare($assignmentSql);

                                $stmt->execute([
                                    ':lessonId' => $lessonId,
                                    ':structure' => $structure,
                                ]);
                                $assignmentId = $this->db->lastInsertId();
                                $assignmentSql = "INSERT INTO assignment_files (AssignmentId, AssignmentNo, AssignmentName, Link) VALUES (:AssignmentId, :AssignmentNo, :AssignmentName, :Link)";
                                $stmt = $this->db->prepare($assignmentSql);

                                $stmt->execute([
                                    ':AssignmentId' => $assignmentId,
                                    ':AssignmentNo' => $assignmentNo1,
                                    ':AssignmentName' => $assignmentName1,
                                    ':Link' => $Link1,
                                ]);

                                $stmt->execute([
                                    ':AssignmentId' => $assignmentId,
                                    ':AssignmentNo' => $assignmentNo2,
                                    ':AssignmentName' => $assignmentName2,
                                    ':Link' => $Link2,
                                ]);

                                $stmt->execute([
                                    ':AssignmentId' => $assignmentId,
                                    ':AssignmentNo' => $assignmentNo3,
                                    ':AssignmentName' => $assignmentName3,
                                    ':Link' => $Link3,
                                ]);


                                $this->db->commit();
                                header('Content-Type: application/json');
                                echo json_encode([
                                    'status' => 'success',
                                    'message' => 'Lesson and steps added successfully.',
                                ]);
                            } catch (\PDOException $e) {
                                // Rollback transaction if something goes wrong
                                $this->db->rollBack();
                                header('Content-Type: application/json');
                                echo json_encode([
                                    'status' => 'error',
                                    'message' => 'Error adding lesson: ' . $e->getMessage(),
                                ]);
                            }
                        } else {
                            echo json_encode(['error' => 'Invalid request method.']);
                            return;
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
}
