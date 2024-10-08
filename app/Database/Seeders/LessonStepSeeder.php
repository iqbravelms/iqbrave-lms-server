<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class LessonStepSeeder
{
    private $db;

    public function __construct()
    {
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

        // Use environment variables for the database connection
        $this->db = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8",
            $user,
            $password
        );
    }

    public function run()
{
    // Insert sample data into the courses table
    $sql = "
        INSERT INTO lesson_steps (
            id, 
            LessonId, 
            StepNo, 
            description
        ) VALUES
        (NULL, 1,1,'go to xampp.com'),
        (NULL, 1,2,'click download button'),
        (NULL, 1,3,'install'),
        (NULL, 2,1,'opean xampp'),
        (NULL, 2,2,'type create databases;'),
        (NULL, 2,3,'check letters'),
        (NULL, 2,4,'then enter')
    ";

    // $this->db->exec($sql);
    // echo "lesson_steps table seeded successfully.\n";
}

}
