<?php
include_once 'database.php';
session_start();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $admin_id = $data['admin_id'] ?? null;
    $name = trim($data['name'] ?? '');
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? '';

    if ($action === 'add') {
        if (empty($name) || empty($password) || empty($role)) {
            echo json_encode(['error' => 'Tous les champs sont obligatoires.']);
            exit;
        }

        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo = getDatabaseConnection();
            $query = "INSERT INTO admins (name, password, role) VALUES (:name, :password, :role)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':password' => $hashed_password,
                ':role' => $role
            ]);

            // Enregistrement des logs
            $logged = $_SESSION['admin_id'] ?? null;
            if ($logged) {
                $action = "Nouvel administrateur ajouté : $name";
                $sql = "INSERT INTO admin_logs (admin_id, actions) VALUES (:admin_id, :actions)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':admin_id' => $logged,
                    ':actions' => $action
                ]);
            }

            echo json_encode(['message' => 'Administrateur ajouté avec succès.']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    } elseif ($action === 'delete' && $admin_id) {
        try {
            $pdo = getDatabaseConnection();
            $query = "DELETE FROM admins WHERE id = :admin_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':admin_id' => $admin_id]);

            // Enregistrement des logs
            $logged = $_SESSION['admin_id'] ?? null;
            if ($logged) {
                $action = "Administrateur supprimé : ID $admin_id";
                $sql = "INSERT INTO admin_logs (admin_id, actions) VALUES (:admin_id, :actions)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':admin_id' => $logged,
                    ':actions' => $action
                ]);
            }

            echo json_encode(['message' => 'Administrateur supprimé avec succès.']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Action non valide.']);
    }
} else {
    echo json_encode(['error' => 'Méthode non autorisée.']);
}
?>