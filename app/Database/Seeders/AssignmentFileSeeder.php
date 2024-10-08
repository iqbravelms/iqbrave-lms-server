<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class AssignmentFileSeeder
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
        INSERT INTO assignment_files (
            id, 
            AssignmentId,
            AssignmentNo,
            AssignmentName,
            Link
        ) VALUES
        (NULL, 1,1,'xampp install assignment 1','https://drive.google.com/file/d/19Q8vidXYk4J2c15AWSid9Dp_bHY9Pkh5/view?usp=sharing'),
        (NULL, 1,2,'xampp install assignment 2','https://drive.google.com/file/d/1OkxwLngHUOlTTGHaxLRMsriQA_Lh1KTQ/view?usp=sharing'),
        (NULL, 1,3,'xampp install assignment 3','https://drive.google.com/file/d/1ja8U8uX9GR1sm2rioisnHonbfibClx78/view?usp=sharing'),
        (NULL, 2,1,'xampp install assignment 1','https://drive.google.com/file/d/19Q8vidXYk4J2c15AWSid9Dp_bHY9Pkh5/view?usp=sharing'),
        (NULL, 2,2,'xampp install assignment 2','https://drive.google.com/file/d/1OkxwLngHUOlTTGHaxLRMsriQA_Lh1KTQ/view?usp=sharing'),
        (NULL, 2,3,'xampp install assignment 3','https://drive.google.com/file/d/1ja8U8uX9GR1sm2rioisnHonbfibClx78/view?usp=sharing'),
        (NULL, 3,1,'xampp install assignment 1','https://drive.google.com/file/d/19Q8vidXYk4J2c15AWSid9Dp_bHY9Pkh5/view?usp=sharing'),
        (NULL, 3,2,'xampp install assignment 2','https://drive.google.com/file/d/1OkxwLngHUOlTTGHaxLRMsriQA_Lh1KTQ/view?usp=sharing'),
        (NULL, 3,3,'xampp install assignment 3','https://drive.google.com/file/d/1ja8U8uX9GR1sm2rioisnHonbfibClx78/view?usp=sharing'),
        (NULL, 4,1,'xampp install assignment 1','https://drive.google.com/file/d/19Q8vidXYk4J2c15AWSid9Dp_bHY9Pkh5/view?usp=sharing'),
        (NULL, 4,2,'xampp install assignment 2','https://drive.google.com/file/d/1OkxwLngHUOlTTGHaxLRMsriQA_Lh1KTQ/view?usp=sharing'),
        (NULL, 4,3,'xampp install assignment 3','https://drive.google.com/file/d/1ja8U8uX9GR1sm2rioisnHonbfibClx78/view?usp=sharing')
    ";

        // $this->db->exec($sql);
        // echo "assignment_files table seeded successfully.\n";
    }
}
