<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../', '.env');
$dotenv->load();


class StudentSeeder
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
        // Insert sample data into the student table
        $sql = "
    INSERT INTO students (
        id, 
        firstname, 
        lastname, 
        dob, 
        mobile, 
        whatsapp, 
        email, 
        address, 
        district, 
        city, 
        nic, 
        caretaker, 
        CaretakerMobile, 
        username, 
        password, 
        StuId, 
        status
    ) VALUES
    (NULL, 'Mahesh', 'Perera', '2000-01-01', '0771234567', '0771234567', 'mahesh@example.com', '123 Street', 'District1', 'City1', '901234567V', 'Caretaker1', '0771111111', 'maheshp', 'password1', 'stu001', false),
    (NULL, 'Sachi', 'Kumar', '2001-02-02', '0779876543', '0779876543', 'sachi@example.com', '456 Avenue', 'District2', 'City2', '912345678V', 'Caretaker2', '0772222222', 'sachik', 'password1', 'stu002', false),
    (NULL, 'Jane', 'Doe', '2002-03-03', '0776543210', '0776543210', 'jane@example.com', '789 Road', 'District3', 'City3', '923456789V', 'Caretaker3', '0773333333', 'janed', 'password2', 'stu003', false),
    (NULL, 'John', 'Smith', '2003-04-04', '0781234567', '0781234567', 'john@example.com', '101 Street', 'District4', 'City4', '934567890V', 'Caretaker4', '0784444444', 'johns', 'password3', 'stu004', false),
    (NULL, 'Emily', 'Johnson', '2004-05-05', '0789876543', '0789876543', 'emily@example.com', '202 Avenue', 'District5', 'City5', '945678901V', 'Caretaker5', '0785555555', 'emilyj', 'password4', 'stu005', false)
";


        $this->db->exec($sql);
        echo "Students table seeded successfully.\n";
    }
}
