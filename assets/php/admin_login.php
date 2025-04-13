<?php
include_once 'database.php';
session_start();
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
        header('Location: ../../page/ajouter_type.php');
        exit; 
}}
?>
