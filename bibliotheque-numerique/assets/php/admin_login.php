<?php
include_once 'database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';
    
   

    $pdo = getDatabaseConnection();

    $query = "SELECT * FROM admins WHERE name = :name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if($admin && ($password==$admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['name'];
        $_SESSION['admin_role'] = 'admin';
        
        header('Location: ../../page/afficher_documents.php');
        exit; 
    } else {
        header('Location: ../../page/login.php?error=1');
        exit;
    }
}
?>
