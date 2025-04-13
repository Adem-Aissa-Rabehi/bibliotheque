<?php
session_start();
header("Content-Type: application/json");

$theme = $_POST['theme'] ?? null;

if (!$theme) {
    echo json_encode(["success" => false, "message" => "Paramètre manquant."]);
    exit;
}

// Stocker le thème dans la session
$_SESSION['theme'] = $theme;

echo json_encode(["success" => true, "message" => "Paramètres enregistrés avec succès."]);
?>