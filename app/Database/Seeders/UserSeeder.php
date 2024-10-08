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
        $passwordHash = password_hash('password123', PASSWORD_BCRYPT);
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
            (NULL, 'Mahesh', 'Dissanayaka', '0773132973', '0703295647', 'mahesh9810@gmail.com', 'pahalawela uva palwatta buttala', '981620780V', 'dmahesh9810', '$passwordHash', 'admin', true)
        ";

        $this->db->exec($sql);
        echo "User table seeded successfully.\n";
    }
}
