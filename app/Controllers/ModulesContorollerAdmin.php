<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class ModulesContorollerAdmin
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }
    public function getAllModules($id)
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
                        $stmt = $this->db->prepare("SELECT * FROM modules WHERE CourseId = :id");
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($modules && count($modules) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'modules' => $modules]);
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
    public function addModule()
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
                            try {
                                $courseId = trim($_POST['courseId']);
                                $moduleName = trim($_POST['moduleName']);


                                // Insert data into the database
                                $stmt = $this->db->prepare("
                    INSERT INTO modules 
                    (CourseId  , name  ) 
                    VALUES 
                    (:CourseId , :name )
                ");
                                $stmt->execute([
                                    ':CourseId' => $courseId,
                                    ':name' => $moduleName,
                                ]);

                                echo json_encode(['message' => 'Registration successful', 'StuId' => $stmt]);
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

    public function updateModule()
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
                            $moduleId = trim($_POST['id']);
                            $name = trim($_POST['name']);


                            // Initialize an empty errors array
                            $errors = [];

                            // Firstname validation: required, max length 100
                            if (empty($name) || strlen($name) > 100) {
                                $errors['firstname'] = 'Firstname is required and must be less than 100 characters.';
                            }
                            // Proceed to update the user in the database if validation passes
                            try {
                                // Hash the password if it's provided
                                // Prepare the update statement
                                $sql = "UPDATE modules SET name = :name WHERE id = :id";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':id', $moduleId);
                                $stmt->bindParam(':name', $name);


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

    public function deleteModule($id)
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


                        // Use a prepared statement to safely update user status
                        $stmt = $this->db->prepare("DELETE FROM modules WHERE id = :id");
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
