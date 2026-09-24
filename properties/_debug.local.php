<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debugging Triple K Properties</h1>";

// Check if files exist
echo "<h2>File Check:</h2>";
$files = [
    'includes/config.php',
    'includes/db.php', 
    'includes/functions.php',
    'includes/header.php',
    'includes/footer.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file found<br>";
    } else {
        echo "❌ $file NOT found<br>";
    }
}

// Test database connection
echo "<h2>Database Test:</h2>";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=triple_k_properties", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful<br>";
    
    // Check if tables exist
    $tables = ['t_properties', 't_about', 't_contact', 't_testimonials', 't_faq', 't_chatbot_intents'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "✅ Table '$table' exists with $count rows<br>";
        } catch (PDOException $e) {
            echo "❌ Table '$table' does NOT exist - Please import database.sql<br>";
        }
    }
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test including files
echo "<h2>Include Test:</h2>";
try {
    require_once 'includes/config.php';
    echo "✅ config.php loaded<br>";
} catch (Exception $e) {
    echo "❌ config.php error: " . $e->getMessage() . "<br>";
}

try {
    require_once 'includes/db.php';
    echo "✅ db.php loaded<br>";
} catch (Exception $e) {
    echo "❌ db.php error: " . $e->getMessage() . "<br>";
}

try {
    require_once 'includes/functions.php';
    echo "✅ functions.php loaded<br>";
} catch (Exception $e) {
    echo "❌ functions.php error: " . $e->getMessage() . "<br>";
}

echo "<h2>Your current path:</h2>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
?>