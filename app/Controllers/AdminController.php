<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;
use Exception;

class AdminController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }

    public function getAllAdmin()
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
                        $stmt = $this->db->prepare("SELECT * FROM users");
                        $stmt->execute();
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if ($users && count($users) > 0) {
                            header('Content-Type: application/json');
                            echo json_encode(['status' => 'success', 'users' => $users]);
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
    public function deactivateUser($id)
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
                        $userId = $id; // Fetching user ID from query parameters

                        if ($userId) {
                            // Use a prepared statement to safely update user status
                            $stmt = $this->db->prepare("UPDATE users SET status = false WHERE id = :id");
                            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
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
    public function activateUser($id)
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
                        $userId = $id; // Fetching user ID from query parameters

                        if ($userId) {
                            // Use a prepared statement to safely update user status
                            $stmt = $this->db->prepare("UPDATE users SET status = true WHERE id = :id");
                            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
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



    public function updateAdimin()
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
                            $id = trim($_POST['id']);
                            $firstname = trim($_POST['firstname']);
                            $lastname = trim($_POST['lastname']);
                            $mobile = trim($_POST['mobile']);
                            $whatsapp = trim($_POST['whatsapp']);
                            $email = trim($_POST['email']);
                            $address = trim($_POST['address']);
                            $nic = trim($_POST['nic']);
                            $username = trim($_POST['username']);
                            $role = trim($_POST['role']);
                            $password = trim($_POST['password']);
                            $cpassword = trim($_POST['cpassword']);

                            // Validate form data
                            $errors = [];
                            if (empty($firstname) || strlen($firstname) > 100) {
                                $errors['firstname'] = 'Firstname is required and must be less than 100 characters.';
                            }
                            if (empty($lastname) || strlen($lastname) > 100) {
                                $errors['lastname'] = 'Lastname is required and must be less than 100 characters.';
                            }
                            if (!preg_match('/^\d{10}$/', $mobile)) {
                                $errors['mobile'] = 'Mobile must be a valid 10-digit number.';
                            }
                            if (!preg_match('/^\d{10}$/', $whatsapp)) {
                                $errors['whatsapp'] = 'WhatsApp must be a valid 10-digit number.';
                            }
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 'Email format is invalid.';
                            }
                            if (empty($address) || strlen($address) > 100) {
                                $errors['address'] = 'Address is required and must be less than 100 characters.';
                            }
                            if (empty($nic) || strlen($nic) > 15) {
                                $errors['nic'] = 'NIC is required and must be less than 15 characters.';
                            }
                            if (empty($username) || strlen($username) > 20) {
                                $errors['username'] = 'Username is required and must be less than 20 characters.';
                            }
                            if ($password !== $cpassword) {
                                $errors['password'] = 'Passwords do not match.';
                            }

                            if (!empty($errors)) {
                                http_response_code(400);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'errors' => $errors]);
                                return;
                            }

                            // Proceed to update the user in the database if validation passes
                            try {
                                // Hash the password if it's provided
                                $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;



                                // Prepare the update statement
                                $sql = "UPDATE users SET 
                                            firstname = :firstname,
                                            lastname = :lastname,
                                            mobile = :mobile,
                                            whatsapp = :whatsapp,
                                            email = :email,
                                            address = :address,
                                            nic = :nic,
                                            username = :username,
                                            role = :role" .
                                    ($hashedPassword ? ", password = :password" : "") . // Only include password if it's provided
                                    " WHERE id = :id";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':firstname', $firstname);
                                $stmt->bindParam(':lastname', $lastname);
                                $stmt->bindParam(':mobile', $mobile);
                                $stmt->bindParam(':whatsapp', $whatsapp);
                                $stmt->bindParam(':email', $email);
                                $stmt->bindParam(':address', $address);
                                $stmt->bindParam(':nic', $nic);
                                $stmt->bindParam(':username', $username);
                                $stmt->bindParam(':role', $role);
                                $stmt->bindParam(':id', $id);

                                if ($hashedPassword) {
                                    $stmt->bindParam(':password', $hashedPassword);
                                }

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
    public function adminRegister()
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
                            $firstname = trim($_POST['firstname']);
                            $lastname = trim($_POST['lastname']);
                            $mobile = trim($_POST['mobile']);
                            $whatsapp = trim($_POST['whatsapp']);
                            $email = trim($_POST['email']);
                            $address = trim($_POST['address']);
                            $nic = trim($_POST['nic']);
                            $username = trim($_POST['username']);
                            $role = trim($_POST['role']);
                            $password = trim($_POST['password']);
                            $cpassword = trim($_POST['cpassword']);

                            // Validate form data
                            $errors = [];
                            if (empty($firstname) || strlen($firstname) > 100) {
                                $errors['firstname'] = 'Firstname is required and must be less than 100 characters.';
                            }
                            if (empty($lastname) || strlen($lastname) > 100) {
                                $errors['lastname'] = 'Lastname is required and must be less than 100 characters.';
                            }
                            if (!preg_match('/^\d{10}$/', $mobile)) {
                                $errors['mobile'] = 'Mobile must be a valid 10-digit number.';
                            }
                            if (!preg_match('/^\d{10}$/', $whatsapp)) {
                                $errors['whatsapp'] = 'WhatsApp must be a valid 10-digit number.';
                            }
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 'Email format is invalid.';
                            }
                            if (empty($address) || strlen($address) > 100) {
                                $errors['address'] = 'Address is required and must be less than 100 characters.';
                            }
                            if (empty($nic) || strlen($nic) > 15) {
                                $errors['nic'] = 'NIC is required and must be less than 15 characters.';
                            }
                            if (empty($username) || strlen($username) > 20) {
                                $errors['username'] = 'Username is required and must be less than 20 characters.';
                            }
                            if ($password !== $cpassword) {
                                $errors['password'] = 'Passwords do not match.';
                            }

                            if (!empty($errors)) {
                                http_response_code(400);
                                header('Content-Type: application/json');
                                echo json_encode(['status' => 'error', 'errors' => $errors]);
                                return;
                            }

                            // Proceed to check for uniqueness
                            try {
                                $checkSql = "SELECT COUNT(*) FROM users WHERE 
                                                mobile = :mobile OR 
                                                whatsapp = :whatsapp OR 
                                                email = :email OR 
                                                nic = :nic OR 
                                                username = :username";

                                $stmt = $this->db->prepare($checkSql);

                                // Bind parameters
                                $stmt->bindParam(':mobile', $mobile);
                                $stmt->bindParam(':whatsapp', $whatsapp);
                                $stmt->bindParam(':email', $email);
                                $stmt->bindParam(':nic', $nic);
                                $stmt->bindParam(':username', $username);

                                // Execute the statement
                                $stmt->execute();
                                $count = $stmt->fetchColumn();

                                if ($count > 0) {
                                    http_response_code(400);
                                    echo json_encode(['status' => 'error', 'message' => 'One or more fields already exist.']);
                                    return;
                                }

                                // Hash the password if it's provided
                                $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;

                                // Prepare the insert statement
                                $sql = "INSERT INTO users (firstname, lastname, mobile, whatsapp, email, address, nic, username, role" .
                                    ($hashedPassword ? ", password" : "") .
                                    ") VALUES (:firstname, :lastname, :mobile, :whatsapp, :email, :address, :nic, :username, :role" .
                                    ($hashedPassword ? ", :password" : "") .
                                    ")";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':firstname', $firstname);
                                $stmt->bindParam(':lastname', $lastname);
                                $stmt->bindParam(':mobile', $mobile);
                                $stmt->bindParam(':whatsapp', $whatsapp);
                                $stmt->bindParam(':email', $email);
                                $stmt->bindParam(':address', $address);
                                $stmt->bindParam(':nic', $nic);
                                $stmt->bindParam(':username', $username);
                                $stmt->bindParam(':role', $role);

                                if ($hashedPassword) {
                                    $stmt->bindParam(':password', $hashedPassword);
                                }

                                // Execute the statement
                                $stmt->execute();

                                echo json_encode(['success' => true, 'message' => 'User inserted successfully.']);
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
