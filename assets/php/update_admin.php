<?php
include_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['admin_id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? '');

    if (!$adminId || empty($name) || empty($role)) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit;
    }

    try {
        $pdo = getDatabaseConnection();
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Mettre à jour l'administrateur
        $stmt = $pdo->prepare("UPDATE admins SET name = :name, password = :password, role = :role WHERE id = :admin_id");
        $stmt->execute([
            ':name' => $name,
            ':password' => $hashed_password,
            ':role' => $role,
            ':admin_id' => $adminId
        ]);

        // Enregistrement des logs
        $logged = $_SESSION['admin_id'] ?? null;
        if ($logged) {
            $action = "Administrateur mis à jour : ID $adminId";
            $sql = "INSERT INTO admin_logs (admin_id, actions) VALUES (:admin_id, :actions)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $logged,
                ':actions' => $action
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'Administrateur mis à jour avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>