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
        $passwordHash = password_hash('password', PASSWORD_BCRYPT);
        // Insert sample data into the users table
        $sql = "
            INSERT INTO users (
                id, 
                firstname, 
                lastname, 
                mobile, 
                whatsapp, 
                email, 
                address, 
                nic, 
                username, 
                password, 
                role, 
                status
            ) VALUES
            (NULL, 'Mahesh', 'Perera', '0771234567', '0771234567', 'mahesh@example.com', '123 Street', '901234567V', 'dmahesh9810', '$passwordHash', 'admin', false),
            (NULL, 'Sachi', 'Kumar', '0779876543', '0779876543', 'sachi@example.com', '456 Avenue', '912345678V', 'sachik', '$passwordHash', 'moderator', false),
            (NULL, 'Jane', 'Doe', '0776543210', '0776543210', 'jane@example.com', '789 Road', '923456789V', 'janed', '$passwordHash', 'viewer', false)
        ";

        $this->db->exec($sql);
        echo "Users table seeded successfully.\n";
    }
}
