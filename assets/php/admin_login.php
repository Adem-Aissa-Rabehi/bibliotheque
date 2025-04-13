<?php
include_once 'database.php';
session_start();
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($password)) {
        echo json_encode(['success' => false, 'message' => "Nom d'utilisateur ou mot de passe manquant."]);
        exit;
    }

    try {
        $pdo = getDatabaseConnection();
        $query = "SELECT * FROM admins WHERE name = :name";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':name' => $name]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            echo json_encode(['success' => true, 'message' => "Connexion réussie."]);
        } else {
            echo json_encode(['success' => false, 'message' => "Nom d'utilisateur ou mot de passe incorrect."]);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "Erreur de base de données : " . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Méthode non autorisée."]);
}
?>