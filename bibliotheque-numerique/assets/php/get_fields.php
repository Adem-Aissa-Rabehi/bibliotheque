<?php
require_once 'database.php';
header('Content-Type: application/json');

// Vérifier si l'ID du type est passé en paramètre
if (!isset($_GET['type_id']) || empty($_GET['type_id'])) {
    echo json_encode(['success' => false, 'message' => 'Type de document invalide.']);
    exit;
}

$typeId = intval($_GET['type_id']);

try {
    $pdo = getDatabaseConnection();

    // Récupérer les champs associés au type sélectionné
    $stmt = $pdo->prepare("SELECT id, name, type FROM fields WHERE type_id = :type_id");
    $stmt->execute([':type_id' => $typeId]);
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'fields' => $fields]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
}

