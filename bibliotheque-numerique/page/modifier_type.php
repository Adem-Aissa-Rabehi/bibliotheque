<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un type de document - Bibliothèque Numérique</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/type.css">
</head>
<body>
    <div class="content">
        <h1>Modifier un type de document</h1>
        
        <form id="updateTypeForm">
            <div class="form-group">
                <label for="typeName">Nom du type :</label>
                <input type="text" id="typeName" name="typeName" required>
            </div>

            <div class="form-group">
                <label>Champs :</label>
                <div id="fieldsContainer"></div>
                <button type="button" id="addField" class="btn">Ajouter un champ</button>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="gestion_types.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>

    <script src="assets/js/modifier_type.js"></script>
</body>
</html> 