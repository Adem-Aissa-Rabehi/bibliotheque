<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé'
    ]);
    exit;
}

// Récupérer l'ID du document à supprimer
$data = json_decode(file_get_contents('php://input'), true);
$document_id = isset($data['id']) ? intval($data['id']) : null;

if (!$document_id) {
    echo json_encode([
        'success' => false,
        'message' => 'ID du document non spécifié'
    ]);
    exit;
}

try {
    // Commencer une transaction
    $pdo->beginTransaction();
    
    // Supprimer les champs du document
    $stmt = $pdo->prepare("DELETE FROM document_fields WHERE document_id = ?");
    $stmt->execute([$document_id]);
    
    // Supprimer le document
    $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
    $stmt->execute([$document_id]);
    
    // Valider la transaction
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Document supprimé avec succès'
    ]);
    
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la suppression du document: ' . $e->getMessage()
    ]);
}
?>

