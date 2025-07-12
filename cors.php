<?php
// cors.php - Complete CORS disable
// Allow from any origin
header("Access-Control-Allow-Origin: *");

// Allow credentials
header("Access-Control-Allow-Credentials: true");

// Specify allowed methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD, PATCH");

// Specify allowed headers - comprehensive list
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, Cache-Control, Pragma, Expires, X-HTTP-Method-Override, X-Forwarded-For, X-Real-IP, User-Agent, Referer, Accept-Encoding, Accept-Language, Connection, Host");

// Expose headers
header("Access-Control-Expose-Headers: Content-Length, X-JSON");

// Max age for preflight requests
header("Access-Control-Max-Age: 86400");

// Handle preflight requests (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Return 200 OK for preflight
    http_response_code(200);
    exit(0);
}
?>