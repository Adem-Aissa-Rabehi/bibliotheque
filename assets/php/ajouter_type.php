<?php
require_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $fieldNames = $_POST['field_names'] ?? [];
    $fieldTypes = $_POST['field_types'] ?? [];

    if (empty($name) || empty($fieldNames) || empty($fieldTypes)) {
        echo json_encode(['success' => false, 'message' => "Veuillez remplir tous les champs requis."]);
        exit;
    }

    if (count($fieldNames) !== count($fieldTypes)) {
        echo json_encode(['success' => false, 'message' => "Les champs et leurs types ne correspondent pas."]);
        exit;
    }

    try {
        $pdo = getDatabaseConnection();
        $pdo->beginTransaction();

        // Ajouter le type
        $stmt = $pdo->prepare("INSERT INTO types (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        $typeId = $pdo->lastInsertId();

        // Ajouter les champs associés
        $stmt = $pdo->prepare("INSERT INTO fields (name, type, type_id) VALUES (:name, :type, :type_id)");
        foreach ($fieldNames as $index => $fieldName) {
            $stmt->execute([
                ':name' => $fieldName,
                ':type' => $fieldTypes[$index],
                ':type_id' => $typeId
            ]);
        }

        // Enregistrement des logs
        $admin_id = $_SESSION['admin_id'] ?? null;
        if ($admin_id) {
            $action = "Nouveau type de document ajouté: $name";
            $sql = "INSERT INTO admin_logs (admin_id, actions) VALUES (:admin_id, :actions)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $admin_id,
                ':action' => $action
            ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => "Type ajouté avec succès."]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => "Erreur : " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Méthode non autorisée."]);
}
?>