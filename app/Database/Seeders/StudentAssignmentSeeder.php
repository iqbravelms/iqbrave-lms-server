<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class StudentAssignmentSeeder
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
        INSERT INTO student_assignments (
            id, 
            AssignmentFileId,
            StudentId,
            StartDate,
            DueDate,
            SubmitedDate,
            score
        ) VALUES
        (NULL, 1,1,'2024-1-1','2024-1-14','2024-1-14',80),
        (NULL, 2,1,'2024-1-1','2024-1-14','2024-1-14',70),
        (NULL, 3,1,'2024-1-1','2024-1-14','2024-1-14',90),
        (NULL, 4,2,'2024-1-1','2024-1-14','2024-1-14',80),
        (NULL, 5,2,'2024-1-1','2024-1-14','2024-1-14',85),
        (NULL, 6,2,'2024-1-1','2024-1-14','2024-1-14',90),
        (NULL, 7,3,'2024-1-1','2024-1-14','2024-1-14',95),
        (NULL, 8,3,'2024-1-1','2024-1-14','2024-1-14',99),
        (NULL, 9,3,'2024-1-1','2024-1-14','2024-1-14',75),
        (NULL, 10,4,'2024-1-1','2024-1-14','2024-1-14',60),
        (NULL, 11,4,'2024-1-1','2024-1-14','2024-1-14',55),
        (NULL, 12,4,'2024-1-1','2024-1-14','2024-1-14',40)
    ";

        // $this->db->exec($sql);
        // echo "assignment_files table seeded successfully.\n";
    }
}
