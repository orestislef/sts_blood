<?php
// api.php - Fixed to work with your setup

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the CORS handling file
if (file_exists('cors.php')) {
    include 'cors.php';
} else {
    // CORS headers if cors.php doesn't exist
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Credentials: true");
}

// Set content type
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include the database configuration file
if (file_exists('db_config.php')) {
    include 'db_config.php';
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database configuration file not found']);
    exit();
}

try {
    // Get the POST data from the request body
    $input = file_get_contents('php://input');
    
    if (empty($input)) {
        throw new Exception('No input data received');
    }
    
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid JSON data');
    }

    // Check if required fields exist
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

    // Create connection to MySQL database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }

    // Set charset to handle Greek characters properly
    $conn->set_charset("utf8");

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM donors WHERE email = ?");
    if (!$checkStmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    
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

    // Convert the dateOfRegister to MySQL datetime format
    $mysqlDateTime = date('Y-m-d H:i:s', strtotime($dateOfRegister));

    // Prepare and bind the insert statement
    $stmt = $conn->prepare("INSERT INTO donors (fullName, telephoneNumber, email, dateOfRegister) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }

    $stmt->bind_param("ssss", $fullName, $telephoneNumber, $email, $mysqlDateTime);

    // Execute the statement
    if ($stmt->execute()) {
        $insertId = $conn->insert_id;
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Η εγγραφή ολοκληρώθηκε επιτυχώς',
            'id' => $insertId
        ]);
    } else {
        throw new Exception('Database insert error: ' . $stmt->error);
    }

    // Close the connection
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