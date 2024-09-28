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
        (NULL, 1,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 2,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 3,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 4,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 5,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 6,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 7,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 8,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 9,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing'),
        (NULL, 10,'https://drive.google.com/file/d/1FwBbbacLsILfK-fZOTzpH7nP73jOuUX9/view?usp=sharing')
    ";

        $this->db->exec($sql);
        echo "Assignments table seeded successfully.\n";
    }
}
