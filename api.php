<?php
// Include the CORS handling file
include 'cors.php';

// Include the database configuration file
include 'db_config.php';

// Get the POST data from the request body
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $fullName = $data['fullName'];
    $telephoneNumber = $data['telephoneNumber'];
    $email = $data['email'];
    $dateOfRegister = $data['dateOfRegister'];

    // Create connection to MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO donors (fullName, telephoneNumber, email, dateOfRegister) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $telephoneNumber, $email, $dateOfRegister);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Data inserted successfully']);
    } else {
        echo json_encode(['error' => 'Error inserting data: ' . $stmt->error]);
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid data']);
}
?>
