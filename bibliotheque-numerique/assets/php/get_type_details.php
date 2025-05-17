<?php
// Désactiver l'affichage des erreurs
error_reporting(0);
ini_set('display_errors', 0);

// Démarrer la mise en tampon de sortie
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';

// Vider tout tampon de sortie existant
ob_clean();

header('Content-Type: application/json');

// Log pour le débogage
error_log('Début de get_type_details.php');

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    error_log('Erreur: Admin non connecté');
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé'
    ]);
    exit;
}

// Vérifier si l'ID du type est fourni
if (!isset($_GET['id'])) {
    error_log('Erreur: ID non fourni dans la requête');
    echo json_encode([
        'success' => false,
        'message' => 'ID du type non spécifié'
    ]);
    exit;
}

$typeId = intval($_GET['id']);
error_log('ID du type reçu: ' . $typeId);

try {
    // Récupérer les détails du type
    $stmt = $pdo->prepare("SELECT * FROM types WHERE id = :id");
    $stmt->execute(['id' => $typeId]);
    $type = $stmt->fetch(PDO::FETCH_ASSOC);

    error_log('Résultat de la requête type: ' . ($type ? json_encode($type) : 'non trouvé'));

    if (!$type) {
        error_log('Type non trouvé pour ID: ' . $typeId);
        echo json_encode([
            'success' => false,
            'message' => 'Type de document non trouvé'
        ]);
        exit;
    }

    // Récupérer les champs associés au type
    $stmt = $pdo->prepare("SELECT name, type as field_type FROM fields WHERE type_id = :type_id ORDER BY name");
    $stmt->execute(['type_id' => $typeId]);
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log('Nombre de champs trouvés: ' . count($fields));
    error_log('Champs: ' . json_encode($fields));

    // Préparer la réponse
    $response = [
        'success' => true,
        'type' => [
            'id' => $type['id'],
            'name' => $type['name'],
            'fields' => $fields
        ]
    ];

    // Vider tout tampon de sortie existant
    ob_clean();

    error_log('Réponse finale: ' . json_encode($response));
    echo json_encode($response);
    exit;

} catch (PDOException $e) {
    error_log('Erreur PDO: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des détails du type'
    ]);
    exit;
} 