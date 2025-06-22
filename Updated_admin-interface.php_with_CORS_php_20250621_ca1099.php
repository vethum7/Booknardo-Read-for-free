<?php
header('Content-Type: application/json');
require_once 'includes/auth.php';

// CORS headers (if admin and reader are on different domains)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: X-Admin-Token, Content-Type");

// Verify this request is coming from the admin interface
$admin_token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? '';
if ($admin_token !== 'SECURE_ADMIN_TOKEN') {
    http_response_code(403);
    die(json_encode(['error' => 'Unauthorized access']));
}

// Rest of your existing admin-interface.php code...