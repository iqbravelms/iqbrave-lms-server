<?php
require_once 'vendor/autoload.php'; // Ensure this points to your autoload file

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');

$dotenv->load();


class CreateModulesTable
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
        CREATE TABLE IF NOT EXISTS modules (
            id INT AUTO_INCREMENT PRIMARY KEY,
            CourseId INT,
            name VARCHAR(100) NOT NULL UNIQUE,
            FOREIGN KEY (CourseId)
            REFERENCES courses(id)
            ON DELETE CASCADE
        )
    ";
        $this->db->exec($sql);
        echo "Modules table created successfully.\n";
    }

    public function down()
    {
        // SQL query to drop the modules table if it exists
        try {
            $sql = "DROP TABLE IF EXISTS modules";
            $this->db->exec($sql);
            echo "Modules table dropped successfully.\n";
        } catch (Exception $e) {
            echo "Errorsss :" . $e->getMessage();
            echo "--------\n";
        }
    }
}
