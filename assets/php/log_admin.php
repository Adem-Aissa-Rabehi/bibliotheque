<?php
include_once 'database.php';

header("Content-Type: application/json");

$pdo = getDatabaseConnection();



$query = "
    SELECT admin_logs.id, admins.name AS admin_name, admin_logs.action, admin_logs.created_at 
    FROM admin_logs
    JOIN admins ON admin_logs.admin_id = admins.id
    ORDER BY admin_logs.created_at DESC
";
$stmt = $pdo->query($query);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($logs);
?>
