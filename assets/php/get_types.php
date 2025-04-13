<?php
require_once 'database.php';

header('Content-Type: application/json');

try {
    $pdo = getDatabaseConnection();
    $stmt = $pdo->query("SELECT id, name FROM types ORDER BY name");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'types' => $types]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
}
