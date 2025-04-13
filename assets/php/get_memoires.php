<?php
require_once 'database.php';

try {
    $pdo = getDatabaseConnection();
    $query = "SELECT id, title, author, publication_date, document_path FROM documents WHERE type_id = 9"; // Type 9 = Mémoire
    $stmt = $pdo->query($query);
    $memoires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($memoires);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
}
?>