<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Dotenv\Dotenv;

class AuthController
{




    public function login()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        // Access secret key from the .env file
        $secret_key = $_ENV['SECRET_KEY'];
        $issued_at = time();
        $expiration_time = $issued_at + 3600; // JWT valid for 1 hour

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Dummy user validation (replace with actual DB check)
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($username === 'testuser' && $password === 'password123') {
                // Create JWT token
                $payload = [
                    'iss' => "http://localhost", // Issuer
                    'iat' => $issued_at,         // Issued at
                    'exp' => $expiration_time,   // Expiration time
                    'data' => [
                        'username' => $username
                    ]
                ];

                $jwt = JWT::encode($payload, $secret_key, 'HS256');

                // Send the JWT token to the frontend
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
