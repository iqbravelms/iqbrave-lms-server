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
        require_once __DIR__ . '/../config/database.php';
        $this->db = $db;
    }

    public function index($id)
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

                if ($decoded) {
                    try {
                        $stmt = $this->db->prepare("SELECT * FROM lessons WHERE ModuleId = ?");
                        $stmt->execute([$id]);

                        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($lessons && count($lessons) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'lessons' => $lessons]);
                        } else {
                            http_response_code(404);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'No lessons found for this module.']);
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

        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $jwt = str_replace('Bearer ', '', $headers['Authorization']);

            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                $stmt = $this->db->prepare("
                    SELECT l.*, ls.* 
                    FROM lessons l 
                    LEFT JOIN lesson_steps ls ON l.id = ls.LessonId 
                    WHERE l.id = ?
                ");
                $stmt->execute([$id]);
                $lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($lessonData) {
                    $lessonId = $lessonData[0]['id'];
                    $lesson = [
                        'id' => $lessonId,
                        'topic' => $lessonData[0]['topic'],
                        'link' => $lessonData[0]['link'],
                        'Note' => $lessonData[0]['Note'],
                        'steps' => [],
                    ];

                    foreach ($lessonData as $row) {
                        if ($row['LessonId'] !== null) {
                            $lesson['steps'][] = [
                                'step_id' => $row['id'],
                                'description' => $row['description'],

                            ];
                        }
                    }

                    echo json_encode(['status' => 'success', 'lesson' => $lesson]);
                } else {

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
