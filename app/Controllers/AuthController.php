<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Dotenv\Dotenv;
use PDO;

class AuthController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php'; 
        $this->db = $db; 
    }
    public function signin()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();
    
        $secret_key = $_ENV['SECRET_KEY'];
        $issued_at = time();
        $expiration_time = $issued_at + 3600; // JWT valid for 1 hour
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'];
            $password = $_POST['password'];
    
            $stmt = $this->db->prepare("SELECT password FROM students WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['password'])) {
        
                $payload = [
                    'iss' => "http://localhost", // Issuer
                    'iat' => $issued_at,         // Issued at
                    'exp' => $expiration_time,   // Expiration time
                    'data' => [
                        'username' => $username
                    ]
                ];
    
                $jwt = JWT::encode($payload, $secret_key, 'HS256');
    
                echo json_encode([
                    'message' => 'Login successful',
                    'token' => $jwt
                ]);
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Invalid credentials']);
            }
        }
    }
    
}
