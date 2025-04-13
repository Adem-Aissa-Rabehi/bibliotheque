<?php
include_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['admin_id'] ?? null;

    if (!$adminId) {
        echo json_encode(['success' => false, 'message' => 'ID administrateur manquant.']);
        exit;
    }

    try {
        $pdo = getDatabaseConnection();

        // Suppression de l'administrateur
        $stmt = $pdo->prepare("DELETE FROM admins WHERE id = :admin_id");
        $stmt->execute([':admin_id' => $adminId]);

        // Enregistrement des logs
        $logged = $_SESSION['admin_id'] ?? null;
        if ($logged) {
            $action = "Administrateur supprimé : ID $adminId";
            $sql = "INSERT INTO admin_logs (admin_id, actions) VALUES (:admin_id, :actions)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $logged,
                ':actions' => $action
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'Administrateur supprimé avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>