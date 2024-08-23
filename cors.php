<?php
// Allow from any origin
header("Access-Control-Allow-Origin: *");

// Specify allowed methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Specify allowed headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Just exit because the headers are already sent
    exit(0);
}
?>
