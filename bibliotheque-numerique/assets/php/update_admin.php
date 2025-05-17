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

$admin_id = $_POST['admin_id'] ?? null;
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');

if (!$admin_id || !$username || !$role) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
    exit;
}

$valid_roles = ['admin', 'bibliothecaire', 'prof'];
if (!in_array($role, $valid_roles)) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

try {
    // Check if username exists for another admin
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE name = ? AND id != ?");
    $stmt->execute([$username, $admin_id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }
    
    // Update admin
    if (!empty($password)) {
        $stmt = $pdo->prepare("UPDATE admins SET name = ?, password = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $password, $role, $admin_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE admins SET name = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $role, $admin_id]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Admin updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 