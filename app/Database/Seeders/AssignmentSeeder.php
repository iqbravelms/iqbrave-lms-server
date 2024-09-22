<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class AssignmentSeeder
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
        INSERT INTO assignments (
            id, 
            LessonId,
            structure
        ) VALUES
        (NULL, 1,'image1.pdf'),
        (NULL, 2,'image2.pdf'),
        (NULL, 3,'image3.pdf'),
        (NULL, 4,'image4.pdf'),
        (NULL, 5,'image5.pdf'),
        (NULL, 6,'image6.pdf'),
        (NULL, 7,'image7.pdf'),
        (NULL, 8,'image8.pdf'),
        (NULL, 9,'image9.pdf'),
        (NULL, 10,'image10.pdf')
    ";

        $this->db->exec($sql);
        echo "Assignments table seeded successfully.\n";
    }
}
