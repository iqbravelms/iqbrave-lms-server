<?php
require_once __DIR__ . '/../../vendor/autoload.php';  // Ensure correct path to autoload.php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');  // Load environment variables
$dotenv->load();

try {
    // Fetch environment variables
    $host = $_ENV['DB_HOST'];
    $port = $_ENV['DB_PORT'];
    $dbName = $_ENV['DB_NAME'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASSWORD'];

    // Check if variables are loaded correctly
    if (!$host || !$port || !$dbName || !$user) {
        throw new Exception("Database configuration is not set properly.");
    }

    // Create the database connection
    $db = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]  // Enable exception mode for errors
    );
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
