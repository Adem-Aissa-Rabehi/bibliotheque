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

try {
    // Récupérer tous les types de documents
    $stmt = $pdo->query("SELECT id, name FROM types ORDER BY name");
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'types' => $types
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des types: ' . $e->getMessage()
    ]);
}
?>
