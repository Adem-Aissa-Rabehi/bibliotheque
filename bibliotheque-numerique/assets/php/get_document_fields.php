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

// Récupérer les paramètres
$type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : null;
$document_id = isset($_GET['document_id']) ? intval($_GET['document_id']) : null;

if (!$type_id) {
    echo json_encode([
        'success' => false,
        'message' => 'Type de document non spécifié'
    ]);
    exit;
}

try {
    // Récupérer les champs du type de document
    $stmt = $pdo->prepare("
        SELECT f.id, f.name, f.type
        FROM fields f
        WHERE f.type_id = ?
        ORDER BY f.id
    ");
    $stmt->execute([$type_id]);
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si un document_id est fourni, récupérer les valeurs existantes
    if ($document_id) {
        $stmt = $pdo->prepare("
            SELECT f.id, df.field_value as value
            FROM document_fields df
            JOIN fields f ON df.field_id = f.id
            WHERE df.document_id = ?
        ");
        $stmt->execute([$document_id]);
        $values = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Fusionner les valeurs avec les champs
        foreach ($fields as &$field) {
            $field['value'] = $values[$field['id']] ?? '';
        }
    }
    
    echo json_encode([
        'success' => true,
        'fields' => $fields
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des champs: ' . $e->getMessage()
    ]);
}
?>
