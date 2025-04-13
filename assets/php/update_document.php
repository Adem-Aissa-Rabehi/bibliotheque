<?php
require_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['document_id'] ?? null;
    $typeId = $_POST['document_type'] ?? null;
    $fields = $_POST['fields'] ?? [];

    if (!$documentId || !$typeId) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit;
    }

    try {
        $pdo = getDatabaseConnection();
        $pdo->beginTransaction();

        // Mettre à jour le type de document
        $stmt = $pdo->prepare("UPDATE documents SET type_id = :type_id WHERE id = :document_id");
        $stmt->execute([
            ':type_id' => $typeId,
            ':document_id' => $documentId
        ]);

        // Supprimer les champs dynamiques existants
        $stmt = $pdo->prepare("DELETE FROM document_fields WHERE document_id = :document_id");
        $stmt->execute([':document_id' => $documentId]);

        // Insérer les nouveaux champs dynamiques
        $stmt = $pdo->prepare("INSERT INTO document_fields (document_id, field_id, field_value) VALUES (:document_id, :field_id, :field_value)");
        foreach ($fields as $fieldId => $fieldValue) {
            $stmt->execute([
                ':document_id' => $documentId,
                ':field_id' => $fieldId,
                ':field_value' => $fieldValue
            ]);
        }

        // Enregistrement des logs
        $admin_id = $_SESSION['admin_id'] ?? null;
        if ($admin_id) {
            $action = "Document mis à jour : ID $documentId";
            $sql = "INSERT INTO admin_logs (admin_id, actions) VALUES (:admin_id, :actions)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $admin_id,
                ':actions' => $action
            ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Document mis à jour avec succès.']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>