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

// Récupérer les filtres
$type_id = isset($_GET['type']) ? intval($_GET['type']) : null;
$date_from = isset($_GET['dateFrom']) ? $_GET['dateFrom'] : null;
$date_to = isset($_GET['dateTo']) ? $_GET['dateTo'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

try {
    // Construire la requête SQL
    $query = "SELECT d.*, t.name as type_name 
              FROM documents d 
              JOIN types t ON d.type_id = t.id 
              WHERE 1=1";
    
    $params = [];
    
    // Ajouter les filtres
    if ($type_id) {
        $query .= " AND d.type_id = ?";
        $params[] = $type_id;
    }
    
    if ($date_from) {
        $query .= " AND d.publication_date >= ?";
        $params[] = $date_from;
    }
    
    if ($date_to) {
        $query .= " AND d.publication_date <= ?";
        $params[] = $date_to;
    }
    
    $query .= " ORDER BY d.publication_date DESC";
    
    // Exécuter la requête
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les champs spécifiques pour chaque document
    foreach ($documents as &$doc) {
        $stmt = $pdo->prepare("
            SELECT f.name, df.field_value as value 
            FROM document_fields df 
            JOIN fields f ON df.field_id = f.id 
            WHERE df.document_id = ?
        ");
        $stmt->execute([$doc['id']]);
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter les champs au document
        foreach ($fields as $field) {
            $doc[$field['name']] = $field['value'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'documents' => $documents
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des documents: ' . $e->getMessage()
    ]);
}
?> 