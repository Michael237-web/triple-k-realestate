<?php
session_start();
require_once '../includes/functions.php';

// Simple admin authentication (you can expand this)
$admin_user = 'admin';
$admin_pass = 'password'; // In production, use hashed passwords

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login - Triple K Properties</title>
        <link rel="stylesheet" href="../assets/css/styles.css">
    </head>
    <body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background: var(--background);">
        <div style="background: white; padding: 40px; border-radius: var(--border-radius); box-shadow: var(--shadow); max-width: 400px; width: 100%;">
            <h2 style="text-align: center; color: var(--primary); margin-bottom: 30px;">Admin Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Get statistics
$db = Database::getInstance();
$propertyCount = $db->query("SELECT COUNT(*) as count FROM t_properties")->fetch()['count'];
$messageCount = $db->query("SELECT COUNT(*) as count FROM t_contact")->fetch()['count'];
$chatCount = $db->query("SELECT COUNT(*) as count FROM t_chatbot_conversations")->fetch()['count'];

// Get recent messages
$recentMessages = $db->query("SELECT * FROM t_contact ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Get recent chats
$recentChats = $db->query("SELECT * FROM t_chatbot_conversations ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Triple K Properties</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            text-align: center;
        }
        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 10px;
        }
        .stat-card h3 {
            font-size: 2rem;
            color: var(--primary);
        }
        .stat-card p {
            color: var(--text-light);
        }
    </style>
</head>
<body>
    <div style="background: var(--primary); color: white; padding: 15px 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2><i class="fas fa-user-shield"></i> Admin Dashboard</h2>
                <a href="logout.php" style="color: white; background: rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 50px; text-decoration: none;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="container" style="padding: 40px 0;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px;">
            <div class="stat-card">
                <i class="fas fa-building"></i>
                <h3><?php echo $propertyCount; ?></h3>
                <p>Total Properties</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-envelope"></i>
                <h3><?php echo $messageCount; ?></h3>
                <p>Contact Messages</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-comments"></i>
                <h3><?php echo $chatCount; ?></h3>
                <p>Chat Conversations</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div style="background: white; padding: 25px; border-radius: var(--border-radius); box-shadow: var(--shadow);">
                <h3><i class="fas fa-envelope"></i> Recent Messages</h3>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php foreach ($recentMessages as $msg): ?>
                    <div style="border-bottom: 1px solid #e2e8f0; padding: 15px 0;">
                        <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                        <span style="color: var(--text-light); font-size: 0.9rem;"> - <?php echo htmlspecialchars($msg['email']); ?></span>
                        <p style="margin-top: 5px; color: var(--text-light); font-size: 0.95rem;"><?php echo substr(htmlspecialchars($msg['message']), 0, 100) . '...'; ?></p>
                        <small style="color: var(--text-light);"><?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="background: white; padding: 25px; border-radius: var(--border-radius); box-shadow: var(--shadow);">
                <h3><i class="fas fa-comments"></i> Recent Chat Sessions</h3>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php foreach ($recentChats as $chat): ?>
                    <div style="border-bottom: 1px solid #e2e8f0; padding: 15px 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 600;">Session: <?php echo substr($chat['session_id'], 0, 10) . '...'; ?></span>
                            <small style="color: var(--text-light);"><?php echo date('M d, Y H:i', strtotime($chat['created_at'])); ?></small>
                        </div>
                        <p style="margin: 5px 0;"><strong>User:</strong> <?php echo htmlspecialchars($chat['user_message']); ?></p>
                        <p style="margin: 5px 0;"><strong>Bot:</strong> <?php echo htmlspecialchars($chat['bot_response']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>