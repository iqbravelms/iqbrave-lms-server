<?php
require_once 'vendor/autoload.php'; // Ensure this points to your autoload file

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');

$dotenv->load();


class CreateCoursesTable
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

    public function up()
    {
        // SQL query to create the Students table
        $sql = "
        CREATE TABLE IF NOT EXISTS courses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        )
    ";
        $this->db->exec($sql);
        echo "Courses table created successfully.\n";
    }

    public function down()
    {
        // SQL query to drop the Students table if it exists
        $sql = "DROP TABLE IF EXISTS courses";
        $this->db->exec($sql);
        echo "Courses table dropped successfully.\n";
    }
}