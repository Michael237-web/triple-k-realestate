<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

// Get POST data
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$sessionId = isset($_POST['session_id']) ? $_POST['session_id'] : '';

if (empty($message)) {
    echo json_encode(['success' => false, 'response' => 'Please enter a message.']);
    exit;
}

try {
    // Get response from the enhanced chatbot function
    $response = getChatbotResponse($message);
    
    // Save conversation
    if (!empty($sessionId)) {
        saveChatMessage($sessionId, $message, $response);
    }
    
    echo json_encode([
        'success' => true,
        'response' => $response,
        'show_quick_replies' => true
    ]);
    
} catch (Exception $e) {
    // Log the error
    error_log("Chatbot Error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'response' => "I apologize, but I'm having trouble processing your request. Please try again later or contact us directly:\n\n📞 Phone: +254 700 123456\n📧 Email: info@triplekproperties.co.ke"
    ]);
}
?>