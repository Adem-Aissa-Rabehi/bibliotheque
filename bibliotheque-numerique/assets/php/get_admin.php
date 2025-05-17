<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$admin_id = $_GET['id'] ?? null;

if (!$admin_id) {
    echo json_encode(['success' => false, 'message' => 'Admin ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, role FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        echo json_encode(['success' => false, 'message' => 'Admin not found']);
        exit;
    }
    
    echo json_encode(['success' => true, 'admin' => $admin]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 