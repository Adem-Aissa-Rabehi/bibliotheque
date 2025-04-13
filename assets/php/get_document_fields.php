<?php
require_once 'database.php';

$typeId = isset($_GET['type']) ? $_GET['type'] : null;

if (!$typeId) {
    echo json_encode([]);
    exit;
}

$pdo = getDatabaseConnection();

$query = "SELECT id, name FROM fields WHERE type_id = :typeId";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':typeId', $typeId, PDO::PARAM_INT);
$stmt->execute();

$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($fields);
