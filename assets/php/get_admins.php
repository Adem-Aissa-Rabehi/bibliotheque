<?php
require_once 'database.php';

header("Content-Type: application/json");

try {
    $pdo = getDatabaseConnection();
    $query = "SELECT id, name, role FROM admins ORDER BY id";
    $stmt = $pdo->query($query);
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'admins' => $admins]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
}
?>