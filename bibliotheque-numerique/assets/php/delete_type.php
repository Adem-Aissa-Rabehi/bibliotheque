<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé'
    ]);
    exit;
}

// Vérifier si la méthode est POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
    exit;
}

// Vérifier si l'ID du type est fourni
if (!isset($_POST['type_id']) || empty($_POST['type_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID du type non fourni'
    ]);
    exit;
}

$type_id = intval($_POST['type_id']);

try {
    // Commencer une transaction
    $pdo->beginTransaction();

    // Vérifier si le type existe
    $stmt = $pdo->prepare("SELECT name FROM types WHERE id = ?");
    $stmt->execute([$type_id]);
    $type = $stmt->fetch();

    if (!$type) {
        throw new Exception('Type de document non trouvé');
    }

    // Supprimer les champs associés au type
    $stmt = $pdo->prepare("DELETE FROM fields WHERE type_id = ?");
    $stmt->execute([$type_id]);

    // Supprimer le type
    $stmt = $pdo->prepare("DELETE FROM types WHERE id = ?");
    $stmt->execute([$type_id]);

    // Enregistrer l'action dans les logs
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([
        $_SESSION['admin_id'],
        "Suppression du type de document: " . $type['name']
    ]);

    // Valider la transaction
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Type de document supprimé avec succès'
    ]);

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 