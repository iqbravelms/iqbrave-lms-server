<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class ModuleSeeder
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
    // Insert sample data into the modules table
    $sql = "
        INSERT INTO modules (
            id, 
            CourseId, 
            name
        ) VALUES
        (NULL, 2,'Database Systems 1'),
        (NULL, 2,'Database Systems 11'),
        (NULL, 2,'Software Programming'),
        (NULL, 2,'Software Testing'),
        (NULL, 2,'System Analysis and Design'),
        (NULL, 2,'Web Programming'),
        (NULL, 2,'Local Area Networks'),
        (NULL, 2,'Manage workplace Information'),
        (NULL, 2,'Manage workplace communication'),
        (NULL, 2,'Planning and Scheduling Work at Workplace')
    ";

    // $this->db->exec($sql);
    // echo "Modules table seeded successfully.\n";
}

}
