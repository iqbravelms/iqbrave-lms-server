<?php

namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

class ApiController
{

    public function accessProtectedResource()
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
                echo json_encode(['message' => 'Access granted', 'data' => $decoded->data]);
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
