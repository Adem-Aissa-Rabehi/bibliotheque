<?php
require_once 'database.php';

$pdo = getDatabaseConnection();

$query = "SELECT id, name FROM types";
$stmt = $pdo->query($query);

$types = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($types);
