<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class UserSeeder
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
        // Insert sample data into the users table
        $sql = "
            INSERT INTO users (name, email, password) VALUES
           ('Mahesh', 'Mahesh@example.com', 'password1'),
            ('Sachi', 'sachi@example.com', 'password1'),
            ('Jane', 'jane@example.com', 'password2')
        ";
        $this->db->exec($sql);
        echo "Users table seeded successfully.\n";
    }
}
