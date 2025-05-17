<?php
require_once 'database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $fieldNames = $_POST['field_names'] ?? [];
    $fieldTypes = $_POST['field_types'] ?? [];

    // Validation
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Le nom du type est requis.']);
        exit;
    }

    if (empty($fieldNames) || empty($fieldTypes)) {
        echo json_encode(['success' => false, 'message' => 'Au moins un champ est requis.']);
        exit;
    }

    if (count($fieldNames) !== count($fieldTypes)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs doivent être correctement définis.']);
        exit;
    }

    $pdo = getDatabaseConnection();

    try {
        $pdo->beginTransaction();

        // 1. Insertion dans la table `types`
        $stmt = $pdo->prepare("INSERT INTO types (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        $typeId = $pdo->lastInsertId(); // Récupérer l'ID du type inséré

        // 2. Insertion dans la table `fields`
        $stmt = $pdo->prepare("INSERT INTO fields (name, type, type_id) VALUES (:name, :type, :type_id)");
        foreach ($fieldNames as $index => $fieldName) {
            $stmt->execute([
                ':name' => $fieldName,
                ':type' => $fieldTypes[$index],
                ':type_id' => $typeId
            ]);
        }
        
        $logged = $_SESSION['admin_id'];
        if ($logged) {
            $action = "Nouveau type de document ajouté: $name ";
            $sql = "INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (:admin_id, :actions, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $logged,
                ':actions' => $action
            ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Type et champs ajoutés avec succès.']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>