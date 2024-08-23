<?php
// Include the CORS handling file
include 'cors.php';

// Include the database configuration file
include 'db_config.php';

// Create connection to MySQL
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Connect to the newly created database
$conn->select_db($dbname);

// SQL to create table
$tableSql = "CREATE TABLE IF NOT EXISTS donors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullName VARCHAR(100) NOT NULL,
    telephoneNumber VARCHAR(15) NOT NULL,
    email VARCHAR(50) NOT NULL,
    dateOfRegister TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($tableSql) === TRUE) {
    echo "Table donors created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the connection
$conn->close();
?>
