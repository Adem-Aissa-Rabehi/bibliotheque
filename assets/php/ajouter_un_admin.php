<?php
include_once 'database.php';
session_start();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $action = $data['action'] ?? '';
    $admin_id = $data['admin_id'] ?? null;
    $name = $data['name'] ?? '';
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? '';

    $pdo = getDatabaseConnection();

    if ($action === 'add') {
        if (empty($name) || empty($password) || empty($role)) {
            echo json_encode(['error' => 'Champs obligatoires manquants']);
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO admins (name, password, role) VALUES (:name, :password, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $logged = $_SESSION['admin_id'];
            if ($logged) {
                $action = "Nouvel administrateur ajouté: $name ";
                $sql = "INSERT INTO admin_logs (admin_id, action, timestamp) VALUES (:admin_id, :action, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':admin_id' => $logged,
                    ':action' => $action
                ]);
            }
            echo json_encode(['message' => 'Administrateur ajouté avec succès']);
        } else {
            echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'administrateur']);
        }
    } elseif ($action === 'delete' && $admin_id) {
        $query = "DELETE FROM admins WHERE id = :admin_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':admin_id', $admin_id);

        if ($stmt->execute()) {
            $logged = $_SESSION['admin_id'];
            if ($logged) {
                $action = "Administrateur supprimé: ID $admin_id";
                $sql = "INSERT INTO admin_logs (admin_id, action, timestamp) VALUES (:admin_id, :action, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':admin_id' => $logged,
                    ':action' => $action
                ]);
            }
            echo json_encode(['message' => 'Administrateur supprimé avec succès']);
        } else {
            echo json_encode(['error' => 'Erreur lors de la suppression']);
        }
    }
}
?>
