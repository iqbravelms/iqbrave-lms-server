<?php

namespace App\Controllers;

use PDO;
use DateTime;
use Exception;


class StudentController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; // Adjust path as needed
        $this->db = $db;
    }
    public function index()
    {
        // Get the raw POST data
        $inputData = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize all required fields
        if (
            isset($inputData['firstname'], $inputData['lastname'], $inputData['dob'], $inputData['address'], $inputData['district'], $inputData['city'], $inputData['nic'], $inputData['mobile'], $inputData['whatsapp'], $inputData['caretaker'], $inputData['CaretakerMobile'], $inputData['email'], $inputData['username'], $inputData['password'], $inputData['StuId'], $inputData['status'])
        ) {
            // Validation: Check if any fields are empty
            foreach ($inputData as $key => $value) {
                if (empty($value)) {
                    echo json_encode(['status' => 'error', 'message' => "Field '$key' cannot be empty."]);
                    return;
                }
            }

            // Check for uniqueness in nic, mobile, whatsapp, email, username, and StuId
            $uniqueFields = ['nic', 'mobile', 'whatsapp', 'email', 'username', 'StuId'];
            foreach ($uniqueFields as $field) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM students WHERE $field = ?");
                $stmt->execute([$inputData[$field]]);
                if ($stmt->fetchColumn() > 0) {
                    echo json_encode(['status' => 'error', 'message' => "$field is already in use."]);
                    return;
                }
            }

            // Specific validation for each field (expandable):
            if (!filter_var($inputData['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
                return;
            }

            if (!preg_match("/^(\\d{9}[Vv]|\\d{12})$/", $inputData['nic'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid NIC format.']);
                return;
            }


            if (!preg_match("/^\d{10}$/", $inputData['mobile'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number.']);
                return;
            }

            if (!preg_match("/^\d{10}$/", $inputData['whatsapp'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid WhatsApp number.']);
                return;
            }

            if (!preg_match("/^\d{10}$/", $inputData['CaretakerMobile'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid caretaker mobile number.']);
                return;
            }

            if (!preg_match("/^\w{6,20}$/", $inputData['username'])) {
                echo json_encode(['status' => 'error', 'message' => 'Username must be 6-20 characters long.']);
                return;
            }

            // Validate date format for dob
            if (!DateTime::createFromFormat('Y-m-d', $inputData['dob'])) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid date format for date of birth.']);
                return;
            }

            // Hash the password before storing
            $passwordHash = password_hash($inputData['password'], PASSWORD_BCRYPT);

            // Extract fields
            $firstname = $inputData['firstname'];
            $lastname = $inputData['lastname'];
            $dob = $inputData['dob'];
            $address = $inputData['address'];
            $district = $inputData['district'];
            $city = $inputData['city'];
            $nic = $inputData['nic'];
            $mobile = $inputData['mobile'];
            $whatsapp = $inputData['whatsapp'];
            $caretaker = $inputData['caretaker'];
            $CaretakerMobile = $inputData['CaretakerMobile'];
            $email = $inputData['email'];
            $username = $inputData['username'];
            $password = $passwordHash; // Store the hashed password
            $StuId = $inputData['StuId'];
            $status = $inputData['status'];

            // Insert into database
            $stmt = $this->db->prepare("INSERT INTO students (firstname, lastname, dob, address, district, city, nic, mobile, whatsapp, caretaker, CaretakerMobile, email, username, password, StuId, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $dob, $address, $district, $city, $nic, $mobile, $whatsapp, $caretaker, $CaretakerMobile, $email, $username, $password, $StuId, $status]);

            echo json_encode(['status' => 'success', 'message' => 'Student signed up successfully']);
        } else {
            // Handle the error if required data is missing
            echo json_encode(['status' => 'error', 'message' => 'Required fields are missing']);
        }
    }

    public function studentRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $dob = trim($_POST['dob']);
            $address = trim($_POST['address']);
            $district = trim($_POST['district']);
            $city = trim($_POST['city']);
            $nic = trim($_POST['nic']);
            $mobile = trim($_POST['mobile']);
            $email = trim($_POST['email']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];

            // Validate the form data
            if (empty($firstname) || empty($lastname) || empty($dob) || empty($address) || empty($nic) || empty($email) || empty($username) || empty($password) || empty($cpassword)) {
                error_log('Missing Fields: ' . print_r($_POST, true)); // Log which fields are missing
                echo json_encode(['error' => 'All fields are required.']);
                return;
            }


            // Check if password matches the confirmation password
            if ($password !== $cpassword) {
                echo json_encode(['error' => 'Passwords do not match.']);
                return;
            }

            // Validate password length (minimum 8 characters)
            if (strlen($password) < 8) {
                echo json_encode(['error' => 'Password must be at least 8 characters long.']);
                return;
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Generate unique StuId (e.g., IB00001)
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM students");
            $totalStudents = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            $stuid = 'IB' . str_pad($totalStudents + 1, 5, '0', STR_PAD_LEFT);

            try {
                // Check if NIC, email, or username already exist
                $stmt = $this->db->prepare("SELECT * FROM students WHERE nic = :nic OR email = :email OR username = :username");
                $stmt->execute([
                    ':nic' => $nic,
                    ':email' => $email,
                    ':username' => $username
                ]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode(['error' => 'NIC, Email, or Username already exists.']);
                    return;
                }

                // Insert data into the database
                $stmt = $this->db->prepare("
                    INSERT INTO students 
                    (firstname, lastname, dob, address, district, city, nic, mobile, email, username, password, StuId, status, role) 
                    VALUES 
                    (:firstname, :lastname, :dob, :address, :district, :city, :nic, :mobile, :email, :username, :password, :StuId, :status, :role)
                ");

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
            } catch (Exception $e) {
                echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
            }
        }
    }
}
