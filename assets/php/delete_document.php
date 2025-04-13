<?php
require_once 'database.php';

$pdo = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['document_id'];
    $documentPath = '../'.$_POST['document_path']; // Récupération du chemin du fichier

    try {
        // Suppression du fichier physique
        if (file_exists($documentPath)) {
            if (!unlink($documentPath)) {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du fichier.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Fichier introuvable sur le serveur.']);
            exit;
        }

        // Suppression des champs dynamiques liés au document
        $deleteFieldsStmt = $pdo->prepare("DELETE FROM document_fields WHERE document_id = ?");
        $deleteFieldsStmt->execute([$documentId]);

        // Suppression du document dans la base de données
        $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
        $stmt->execute([$documentId]);

        echo json_encode(['success' => true, 'message' => 'Document supprimé avec succès !']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>

