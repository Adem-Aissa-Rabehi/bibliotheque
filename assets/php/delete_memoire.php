<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memoireId = $_POST['memoire_id'];
    $memoirePath = '../' . $_POST['memoire_path'];

    try {
        $pdo = getDatabaseConnection();

        // Suppression du fichier physique
        if (file_exists($memoirePath)) {
            unlink($memoirePath);
        }

        // Suppression des champs dynamiques liés au mémoire
        $deleteFieldsStmt = $pdo->prepare("DELETE FROM document_fields WHERE document_id = ?");
        $deleteFieldsStmt->execute([$memoireId]);

        // Suppression du mémoire dans la base de données
        $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
        $stmt->execute([$memoireId]);

        echo json_encode(['success' => true, 'message' => 'Mémoire supprimé avec succès !']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>