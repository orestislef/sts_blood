<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type
header('Content-Type: application/json');

// Include database configuration
include 'db_config.php';

try {
    // Get POST data
    $input = file_get_contents('php://input');

    if (empty($input)) {
        throw new Exception('No input data received');
    }

    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception('Invalid JSON data');
    }

    // Validate required fields
    if (!isset($data['fullName']) || !isset($data['telephoneNumber']) || !isset($data['email']) || !isset($data['dateOfRegister'])) {
        throw new Exception('Missing required fields');
    }

    $fullName = trim($data['fullName']);
    $telephoneNumber = trim($data['telephoneNumber']);
    $email = trim($data['email']);
    $dateOfRegister = $data['dateOfRegister'];

    // Basic validation
    if (empty($fullName) || empty($telephoneNumber) || empty($email)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception('Database connection failed');
    }

    $conn->set_charset("utf8");

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM donors WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $checkStmt->close();
        $conn->close();

        http_response_code(409);
        echo json_encode([
            'success' => false,
            'error' => 'Το email υπάρχει ήδη στο σύστημα'
        ]);
        exit();
    }
    $checkStmt->close();

    // Convert date to MySQL format
    $mysqlDateTime = date('Y-m-d H:i:s', strtotime($dateOfRegister));

    // Insert donor
    $stmt = $conn->prepare("INSERT INTO donors (fullName, telephoneNumber, email, dateOfRegister) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $telephoneNumber, $email, $mysqlDateTime);

    if ($stmt->execute()) {
        $insertId = $conn->insert_id;

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Η εγγραφή ολοκληρώθηκε επιτυχώς',
            'id' => $insertId
        ]);
    } else {
        throw new Exception('Database insert error');
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
