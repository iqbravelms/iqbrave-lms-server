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

                // Prepare the statement to fetch the lesson
                $stmt = $this->db->prepare("SELECT * FROM lessons WHERE id = ?");
                $stmt->execute([$id]);
                $lessonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $lessons = []; // Initialize an empty array for lessons

                foreach ($lessonData as $row) {
                    $lessonId = $row['id'];

                    // Initialize the lesson array
                    if (!isset($lessons[$lessonId])) {
                        $lessons[$lessonId] = [
                            'id' => $row['id'],
                            'topic' => $row['topic'],
                            'link' => $row['link'],
                            'Note' => $row['Note'],
                            'steps' => [], // Populate this later
                            'assignments' => [] // Populate this later
                        ];
                    }

                    // Fetch steps for this lesson
                    $stepStmt = $this->db->prepare("SELECT * FROM lesson_steps WHERE LessonId = ?");
                    $stepStmt->execute([$lessonId]);
                    $steps = $stepStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($steps as $step) {
                        // Avoid duplicates in steps
                        if (!in_array($step, $lessons[$lessonId]['steps'])) {
                            $lessons[$lessonId]['steps'][] = $step;
                        }
                    }

                    // Fetch assignments for this lesson
                    $assignmentStmt = $this->db->prepare("SELECT * FROM assignments WHERE LessonId = ?");
                    $assignmentStmt->execute([$lessonId]);
                    $assignments = $assignmentStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($assignments as $assignment) {
                        $assignmentId = $assignment['id'];

                        // Check if the assignment already exists
                        $index = array_search($assignmentId, array_column($lessons[$lessonId]['assignments'], 'id'));
                        if ($index === false) {
                            $lessons[$lessonId]['assignments'][] = [
                                'id' => $assignmentId,
                                'structure' => $assignment['structure'],
                                'files' => [] // Initialize files as an empty array
                            ];
                            $index = count($lessons[$lessonId]['assignments']) - 1; // Get the index of the newly added assignment
                        } else {
                            $index = $index; // Existing assignment index
                        }

                        // Fetch files for this assignment
                        $fileStmt = $this->db->prepare("SELECT * FROM assignment_files WHERE AssignmentId = ?");
                        $fileStmt->execute([$assignmentId]);
                        $files = $fileStmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($files as $file) {
                            // Avoid duplicates in files
                            $fileExists = false;
                            foreach ($lessons[$lessonId]['assignments'][$index]['files'] as $existingFile) {
                                if ($existingFile['name'] === $file['AssignmentName'] && $existingFile['filename'] === $file['FileName']) {
                                    $fileExists = true;
                                    break;
                                }
                            }
                            if (!$fileExists) {
                                $lessons[$lessonId]['assignments'][$index]['files'][] = [
                                    'name' => $file['AssignmentName'],
                                    'filename' => $file['FileName']
                                ];
                            }
                        }
                    }
                }

                // Return the lessons data
                echo json_encode(['status' => 'success', 'lessons' => array_values($lessons)]);
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
