<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get admin ID from POST data
$admin_id = $_POST['admin_id'] ?? null;

if (!$admin_id) {
    echo json_encode(['success' => false, 'message' => 'Admin ID is required']);
    exit;
}

// Prevent deleting yourself
if ($admin_id == $_SESSION['admin_id']) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account']);
    exit;
}

try {
    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Admin not found']);
        exit;
    }
    
    // Delete admin
    $stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    
    // Log the action
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$_SESSION['admin_id'], "Administrateur supprimé: ID $admin_id"]);
    
    echo json_encode(['success' => true, 'message' => 'Admin deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 