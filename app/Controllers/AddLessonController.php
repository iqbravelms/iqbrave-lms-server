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
}
