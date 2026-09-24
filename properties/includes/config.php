<?php
// Database configuration - NO PASSWORD
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'triple_k_properties');

// Site configuration
define('SITE_NAME', 'Triple K Properties');
define('SITE_URL', 'http://localhost/properties/');
define('ADMIN_EMAIL', 'info@triplekproperties.com');
define('PHONE_NUMBER', '+254 46674121');

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
?>