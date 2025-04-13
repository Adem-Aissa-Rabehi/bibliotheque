<?php
require_once 'database.php';

header("Content-Type: application/json");

try {
    $pdo = getDatabaseConnection();

    // Compter les documents
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM documents");
    $documents = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Compter les types
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM types");
    $types = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Compter les administrateurs
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM admins");
    $admins = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Compter les logs
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM admin_logs");
    $logs = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo json_encode([
        "documents" => $documents,
        "types" => $types,
        "admins" => $admins,
        "logs" => $logs
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
}
?>