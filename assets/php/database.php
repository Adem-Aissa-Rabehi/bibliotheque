<?php
function getDatabaseConnection() {
    $host = 'localhost';
    $db_name = 'bibliotheque_numerique';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Fonction centralisée pour les logs
function logAdminAction($pdo, $adminId, $action) {
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (:admin_id, :action, NOW())");
    $stmt->execute([
        ':admin_id' => $adminId,
        ':action' => $action
    ]);
}
?>