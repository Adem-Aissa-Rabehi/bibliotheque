<?php
require_once 'database.php';

$fieldId = isset($_GET['field']) ? $_GET['field'] : null;

if (!$fieldId) {
    echo json_encode([]);
    exit;
}

$pdo = getDatabaseConnection();

$query = "SELECT DISTINCT field_value FROM document_fields WHERE field_id = :fieldId";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':fieldId', $fieldId, PDO::PARAM_INT);
$stmt->execute();

$values = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($values);
