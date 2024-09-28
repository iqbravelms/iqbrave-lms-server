<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class LessonSeeder
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
        INSERT INTO lessons (
            id, 
            ModuleId, 
            topic, 
            link, 
            Note
        ) VALUES
        (NULL, 1,'Install xampp','https://www.youtube.com/embed/UECPZM5HxII','Download XAMPP, run the installer, choose components, select installation directory, finish setup, and start Apache and MySQL servers.'),
        (NULL, 1,'Create Database','https://www.youtube.com/embed/QBEhmkNKjXQ','Open phpMyAdmin, click \"Databases,\" enter name, then click \"Create.\"'),
        (NULL, 1,'Show databases','https://www.youtube.com/embed/UECPZM5HxII','Open phpMyAdmin, click \"Databases\" tab to view existing databases.'),
        (NULL, 1,'Select database','https://www.youtube.com/embed/QBEhmkNKjXQ','Open phpMyAdmin, click database name in sidebar to select it.'),
        (NULL, 1,'Drop database','https://www.youtube.com/embed/UECPZM5HxII','Open phpMyAdmin, select database, click \"Drop,\" confirm to delete.'),
        (NULL, 1,'Create table','https://www.youtube.com/embed/QBEhmkNKjXQ','Open phpMyAdmin, select database, click \"New,\" define columns, then \"Create.\"'),
        (NULL, 1,'Drop table','https://www.youtube.com/embed/UECPZM5HxII','Open phpMyAdmin, select database, click \"New,\" set columns, click \"Create.\"'),
        (NULL, 1,'Insert data to table','https://www.youtube.com/embed/QBEhmkNKjXQ','Open phpMyAdmin, select table, click \"Insert,\" enter data, click \"Go.\"'),
        (NULL, 1,'Update table','https://www.youtube.com/embed/UECPZM5HxII','Open phpMyAdmin, select table, click \"Edit,\" modify data, click \"Go.\"'),
        (NULL, 1,'Delete table','https://www.youtube.com/embed/QBEhmkNKjXQ','Open phpMyAdmin, select table, click \"Drop,\" confirm deletion, click \"Go.\"')
    ";

        $this->db->exec($sql);
        echo "lessons table seeded successfully.\n";
    }
}
