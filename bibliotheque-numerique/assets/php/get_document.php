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

// Vérifier si l'ID du document est fourni
if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID du document non spécifié'
    ]);
    exit;
}

$document_id = intval($_GET['id']);

try {
    // Récupérer les informations de base du document
    $stmt = $pdo->prepare("
        SELECT d.*, t.name as type_name 
        FROM documents d 
        JOIN types t ON d.type_id = t.id 
        WHERE d.id = ?
    ");
    $stmt->execute([$document_id]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$document) {
        echo json_encode([
            'success' => false,
            'message' => 'Document non trouvé'
        ]);
        exit;
    }
    
    // Récupérer les champs spécifiques du document
    $stmt = $pdo->prepare("
        SELECT f.name, df.field_value as value 
        FROM document_fields df 
        JOIN fields f ON df.field_id = f.id 
        WHERE df.document_id = ?
    ");
    $stmt->execute([$document_id]);
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Ajouter les champs au document
    foreach ($fields as $field) {
        $document[$field['name']] = $field['value'];
    }
    
    echo json_encode([
        'success' => true,
        'document' => $document
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération du document: ' . $e->getMessage()
    ]);
}
?>
