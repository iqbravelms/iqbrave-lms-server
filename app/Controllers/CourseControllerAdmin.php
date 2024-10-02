<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class CourseControllerAdmin
{
    private $db;

    public function __construct()
    {
        // Include the database connection
        require_once __DIR__ . '/../config/database.php';  // Ensure the correct path to db.php
        $this->db = $db;  // Assign the $db variable from db.php
    }

    public function getAllCourses()
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
                        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($courses && count($courses) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'courses' => $courses]);
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

    public function updateCourse()
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
                            $id = trim($_POST['id']);
                            $name = trim($_POST['name']);

                            // Initialize an empty errors array
                            $errors = [];

                            // name validation: required, max length 100
                            if (empty($name) || strlen($name) > 100) {
                                $errors['name'] = 'name is required and must be less than 100 characters.';
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
                                $sql = "UPDATE courses SET 
                                            name = :name WHERE id = :id";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':id', $id);


                                // Execute the statement
                                $stmt->execute();

                                echo json_encode(['success' => true, 'message' => 'Courses updated successfully.']);
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

    public function courseRegister()
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

                            $name = trim($_POST['name']);



                            // Validate form data
                            $errors = [];

                            if (empty($name) || strlen($name) > 100) {
                                $errors['name'] = 'name is required and must be less than 100 characters.';
                            }



                            if (!empty($errors)) {
                                http_response_code(400);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'errors' => $errors]);
                                return;
                            }

                            // Proceed to check for uniqueness
                            try {




                                // Insert data into the database
                                $stmt = $this->db->prepare("
                    INSERT INTO courses 
                    (name) 
                    VALUES 
                    (:name)
                ");
                                $stmt->execute([
                                    ':name' => $name
                                ]);

                                echo json_encode(['message' => 'Registration successful', 'data' => $stmt]);
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
    public function deleteCourse($id)
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
                        // Assuming you pass the user ID as a parameter, modify this accordingly
                     

                        if ($id) {
                            // Use a prepared statement to safely update user status
                            $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();

                            // Check if any rows were affected
                            if ($stmt->rowCount() > 0) {
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'success', 'message' => 'User deactivated successfully.']);
                            } else {
                                http_response_code(404);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'message' => 'User not found or already deactivated.']);
                            }
                        } else {
                            http_response_code(400);
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'error', 'message' => 'User ID is required.']);
                        }
                    } catch (\PDOException $e) {
                        http_response_code(500);
                        header('Content-Type: application/json');
                        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                    }
                } else {
                    http_response_code(403); // Forbidden
                    header('Content-Type: application/json');
                    echo json_encode(['message' => 'Access denied: insufficient permissions.']);
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
