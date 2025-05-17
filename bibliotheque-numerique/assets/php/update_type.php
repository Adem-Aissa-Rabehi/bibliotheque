<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Désactiver l'affichage des erreurs
error_reporting(0);
ini_set('display_errors', 0);

require_once 'db_connect.php';

header('Content-Type: application/json');

// Log pour le débogage
error_log('Début de update_type.php');
error_log('POST reçu: ' . print_r($_POST, true));

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    error_log('Erreur: Admin non connecté');
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé'
    ]);
    exit;
}

// Vérifier si les données nécessaires sont présentes
if (!isset($_POST['type_id']) || !isset($_POST['name'])) {
    error_log('Erreur: Données manquantes');
    echo json_encode([
        'success' => false,
        'message' => 'Données manquantes'
    ]);
    exit;
}

$typeId = intval($_POST['type_id']);
$typeName = trim($_POST['name']);
$fieldNames = isset($_POST['field_names']) ? (array)$_POST['field_names'] : [];
$fieldTypes = isset($_POST['field_types']) ? (array)$_POST['field_types'] : [];

error_log('Type ID: ' . $typeId);
error_log('Type Name: ' . $typeName);
error_log('Field Names: ' . print_r($fieldNames, true));
error_log('Field Types: ' . print_r($fieldTypes, true));

try {
    // Démarrer une transaction
    $pdo->beginTransaction();

    // Mettre à jour le nom du type
    $stmt = $pdo->prepare("UPDATE types SET name = :name WHERE id = :id");
    $result = $stmt->execute([
        'name' => $typeName,
        'id' => $typeId
    ]);

    if (!$result) {
        throw new Exception('Erreur lors de la mise à jour du nom du type');
    }

    // Supprimer tous les champs existants
    $stmt = $pdo->prepare("DELETE FROM fields WHERE type_id = :type_id");
    $result = $stmt->execute(['type_id' => $typeId]);

    if (!$result) {
        throw new Exception('Erreur lors de la suppression des anciens champs');
    }

    // Ajouter les nouveaux champs
    if (!empty($fieldNames)) {
        $stmt = $pdo->prepare("INSERT INTO fields (type_id, name, type) VALUES (:type_id, :name, :type)");
        
        foreach ($fieldNames as $i => $name) {
            if (!empty($name) && isset($fieldTypes[$i])) {
                $result = $stmt->execute([
                    'type_id' => $typeId,
                    'name' => $name,
                    'type' => $fieldTypes[$i]
                ]);

                if (!$result) {
                    throw new Exception('Erreur lors de l\'ajout du champ: ' . $name);
                }
            }
        }
    }

    // Valider la transaction
    $pdo->commit();

    // Enregistrer l'action dans les logs
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (:admin_id, :actions, NOW())");
    $stmt->execute([
        ':admin_id' => $_SESSION['admin_id'],
        ':actions' => "Type de document modifié: $typeName"
    ]);

    error_log('Mise à jour réussie');
    echo json_encode([
        'success' => true,
        'message' => 'Type mis à jour avec succès'
    ]);
    exit;

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    error_log('Erreur: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
} 