<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Accès non autorisé'
    ]);
    exit;
}

header("Content-Type: application/json");

// Log pour le débogage
error_log('Méthode: ' . $_SERVER['REQUEST_METHOD']);
error_log('POST: ' . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Vérifier si l'action est 'add'
        if (!isset($_POST['action']) || $_POST['action'] !== 'add') {
            throw new Exception('Action non valide');
        }
        
        // Vérifier si tous les champs requis sont présents
        if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['role'])) {
            throw new Exception('Tous les champs sont obligatoires');
        }
        
        // Récupérer les valeurs
        $name = trim($_POST['username']);
        $password = $_POST['password'];
        $role = trim($_POST['role']);
        
        // Vérifier si les champs ne sont pas vides
        if (empty($name) || empty($password) || empty($role)) {
            throw new Exception('Tous les champs sont obligatoires');
        }
        
        // Connexion à la base de données
        require_once 'db_connect.php';
        
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE name = ?");
        $stmt->execute([$name]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('Ce nom d\'utilisateur existe déjà');
        }
        
        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Commencer une transaction
        $pdo->beginTransaction();
        
        // Ajouter l'administrateur
        $stmt = $pdo->prepare("INSERT INTO admins (name, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$name, $hashed_password, $role]);
        
        // Enregistrer dans les logs
        $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([
            $_SESSION['admin_id'],
            "Nouvel administrateur ajouté: $name"
        ]);
        
        // Valider la transaction
        $pdo->commit();
        
        // Répondre avec succès
        echo json_encode([
            'success' => true,
            'message' => 'Administrateur ajouté avec succès'
        ]);
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        // Log l'erreur
        error_log('Exception: ' . $e->getMessage());
        
        // Répondre avec l'erreur
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    // Répondre avec une erreur si la méthode n'est pas POST
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>
