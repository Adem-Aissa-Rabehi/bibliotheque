<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé'
    ]);
    exit;
}

try {
    // Récupérer les logs avec les informations de l'admin
    $query = "SELECT al.*, a.name as admin_name 
              FROM admin_logs al 
              JOIN admins a ON al.admin_id = a.id 
              ORDER BY al.created_at DESC";
    
    $stmt = $pdo->query($query);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'logs' => $logs
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des logs: ' . $e->getMessage()
    ]);
} 