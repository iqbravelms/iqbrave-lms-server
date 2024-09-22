<?php
require_once 'vendor/autoload.php'; // Ensure this points to your autoload file

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');

$dotenv->load();


class CreateStudentsTable
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
        CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(100) NOT NULL,
            lastname VARCHAR(100) NOT NULL,
            dob DATE NOT NULL,
            address VARCHAR(100) NOT NULL,
            district VARCHAR(15) NOT NULL,
            city VARCHAR(50) NOT NULL,
            nic VARCHAR(15) NOT NULL,
            mobile VARCHAR(12) NOT NULL,
            whatsapp VARCHAR(12) NOT NULL,
            caretaker VARCHAR(50) NOT NULL,
            CaretakerMobile VARCHAR(12) NOT NULL,
            email VARCHAR(100) NOT NULL,
            username VARCHAR(20) NOT NULL,
            password VARCHAR(255) NOT NULL,
            StuId VARCHAR(20) NOT NULL,
            status BOOLEAN NOT NULL DEFAULT false
        )
    ";
        $this->db->exec($sql);
        echo "Students table created successfully.\n";
    }

    public function down()
    {
        // SQL query to drop the Students table if it exists
        $sql = "DROP TABLE IF EXISTS Students";
        $this->db->exec($sql);
        echo "Students table dropped successfully.\n";
    }
}
