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
}
