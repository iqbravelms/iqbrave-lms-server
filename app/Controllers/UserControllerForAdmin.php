<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;
use PDO;

class UserControllerForAdmin
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }

    public function getAllStudent()
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
                        $stmt = $this->db->prepare("SELECT * FROM students");
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
    public function deactivateStudent($id)
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
                            $stmt = $this->db->prepare("UPDATE students SET status = false WHERE id = :id");
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
    public function activateStudent($id)
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
                            $stmt = $this->db->prepare("UPDATE students SET status = true WHERE id = :id");
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

    public function updateStudent()
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
                            $firstname = trim($_POST['firstname']);
                            $lastname = trim($_POST['lastname']);
                            $dob = trim($_POST['dob']);
                            $address = trim($_POST['address']);
                            $district = trim($_POST['district']);
                            $city = trim($_POST['city']);
                            $nic = trim($_POST['nic']);
                            $mobile = trim($_POST['mobile']);
                            $whatsapp = trim($_POST['whatsapp']);
                            $email = trim($_POST['email']);
                            $username = trim($_POST['username']);
                            $password = trim($_POST['password']);
                            $cpassword = trim($_POST['cpassword']);
                            $role = 'user';

                            // Initialize an empty errors array
                            $errors = [];

                            // Firstname validation: required, max length 100
                            if (empty($firstname) || strlen($firstname) > 100) {
                                $errors['firstname'] = 'Firstname is required and must be less than 100 characters.';
                            }

                            // Lastname validation: required, max length 100
                            if (empty($lastname) || strlen($lastname) > 100) {
                                $errors['lastname'] = 'Lastname is required and must be less than 100 characters.';
                            }

                            // Date of Birth validation: required, must be a valid date in the past
                            if (empty($dob)) {
                                $errors['dob'] = 'Date of Birth is required.';
                            } elseif (!strtotime($dob) || strtotime($dob) > time()) {
                                $errors['dob'] = 'Date of Birth must be a valid past date.';
                            }

                            // Address validation: required, max length 100
                            if (empty($address) || strlen($address) > 100) {
                                $errors['address'] = 'Address is required and must be less than 100 characters.';
                            }

                            // District validation: required, max length 15
                            if (empty($district) || strlen($district) > 15) {
                                $errors['district'] = 'District is required and must be less than 15 characters.';
                            }

                            // City validation: required, max length 50
                            if (empty($city) || strlen($city) > 50) {
                                $errors['city'] = 'City is required and must be less than 50 characters.';
                            }

                            // NIC validation: required, max length 15, can contain alphanumeric characters
                            if (empty($nic) || strlen($nic) > 15) {
                                $errors['nic'] = 'NIC is required and must be less than 15 characters.';
                            }

                            // Mobile validation: required, must be exactly 10 digits
                            if (!preg_match('/^\d{10}$/', $mobile)) {
                                $errors['mobile'] = 'Mobile must be a valid 10-digit number.';
                            }

                            // WhatsApp validation: required, must be exactly 10 digits
                            if (!preg_match('/^\d{10}$/', $whatsapp)) {
                                $errors['whatsapp'] = 'WhatsApp must be a valid 10-digit number.';
                            }

                            // Email validation: required, must be a valid email
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $errors['email'] = 'Email format is invalid.';
                            }

                            // Username validation: required, max length 20
                            if (empty($username) || strlen($username) > 20) {
                                $errors['username'] = 'Username is required and must be less than 20 characters.';
                            }

                            // Password validation: required, must match confirmation
                            if (empty($password)) {
                                $errors['password'] = 'Password is required.';
                            } elseif ($password !== $cpassword) {
                                $errors['password'] = 'Passwords do not match.';
                            }

                            // Role validation: required, must be either 'user' or 'admin'
                            if (empty($role) || !in_array($role, ['user', 'admin'])) {
                                $errors['role'] = 'Role is required and must be either "user" or "admin".';
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
                                // Hash the password if it's provided
                                $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;



                                // Prepare the update statement
                                $sql = "UPDATE students SET 
                                            firstname = :firstname,
                                            lastname = :lastname,
                                            dob = :dob,
                                            address = :address,
                                            district = :district,
                                            city = :city,
                                            nic = :nic,
                                            mobile = :mobile,
                                            whatsapp = :whatsapp,
                                            email = :email,
                                            username = :username,
                                            role = :role" .
                                    ($hashedPassword ? ", password = :password" : "") . // Only include password if it's provided
                                    " WHERE id = :id";

                                $stmt = $this->db->prepare($sql);

                                // Bind parameters
                                $stmt->bindParam(':firstname', $firstname);
                                $stmt->bindParam(':lastname', $lastname);
                                $stmt->bindParam(':dob', $dob);
                                $stmt->bindParam(':address', $address);
                                $stmt->bindParam(':district', $district);
                                $stmt->bindParam(':city', $city);
                                $stmt->bindParam(':nic', $nic);
                                $stmt->bindParam(':mobile', $mobile);
                                $stmt->bindParam(':whatsapp', $whatsapp);
                                $stmt->bindParam(':email', $email);
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
    public function registerStudent()
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

                            $firstname = trim($_POST['firstname']);
                            $lastname = trim($_POST['lastname']);
                            $dob = trim($_POST['dob']);
                            $address = trim($_POST['address']);
                            $district = trim($_POST['district']);
                            $city = trim($_POST['city']);
                            $nic = trim($_POST['nic']);
                            $mobile = trim($_POST['mobile']);
                            $whatsapp = trim($_POST['whatsapp']);
                            $email = trim($_POST['email']);
                            $username = trim($_POST['username']);
                            $password = trim($_POST['password']);
                            $cpassword = trim($_POST['cpassword']);
                            $role = 'user';



                            // Validate form data
                            $errors = [];
                            if (empty($dob)) {
                                $errors['dob'] = 'Date of Birth is required.';
                            } elseif (!strtotime($dob) || strtotime($dob) > time()) {
                                $errors['dob'] = 'Date of Birth must be a valid past date.';
                            }
                            if (empty($firstname) || strlen($firstname) > 100) {
                                $errors['firstname'] = 'Firstname is required and must be less than 100 characters.';
                            }
                            if (empty($lastname) || strlen($lastname) > 100) {
                                $errors['lastname'] = 'Lastname is required and must be less than 100 characters.';
                            }
                            // District validation: required, max length 15
                            if (empty($district) || strlen($district) > 15) {
                                $errors['district'] = 'District is required and must be less than 15 characters.';
                            }

                            // City validation: required, max length 50
                            if (empty($city) || strlen($city) > 50) {
                                $errors['city'] = 'City is required and must be less than 50 characters.';
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

                                // Insert data into the database
                                $stmt = $this->db->prepare("
                    INSERT INTO students 
                    (firstname, lastname, dob, address, district, city, nic, mobile, email, username, password, StuId, status, role) 
                    VALUES 
                    (:firstname, :lastname, :dob, :address, :district, :city, :nic, :mobile, :email, :username, :password, :StuId, :status, :role)
                ");
                                $stmt = $this->db->query("SELECT COUNT(*) AS total FROM students");
                                $totalStudents = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                $stuid = 'IB' . str_pad($totalStudents + 1, 5, '0', STR_PAD_LEFT);
                                $stmt->execute([
                                    ':firstname' => $firstname,
                                    ':lastname' => $lastname,
                                    ':dob' => $dob,
                                    ':address' => $address,
                                    ':district' => $district,
                                    ':city' => $city,
                                    ':nic' => $nic,
                                    ':mobile' => $mobile,
                                    ':email' => $email,
                                    ':username' => $username,
                                    ':password' => $hashedPassword,
                                    ':StuId' => $stuid,
                                    ':status' => 0, // default is false (not activated)
                                    ':role' => 'user'
                                ]);

                                echo json_encode(['message' => 'Registration successful', 'StuId' => $stuid]);
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
    public function deleteStudent($id)
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
                            $stmt = $this->db->prepare("DELETE FROM students WHERE id = :id");
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
}
