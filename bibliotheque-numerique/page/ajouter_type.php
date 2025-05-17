<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/type.css" >
    <title>Ajouter Type </title>
</head>
<body>
    <!-- Inclure la barre latérale -->
    <?php include 'SIde-barre.php'; ?>

    <div class="content">
        <h1>Ajouter un Type de Document</h1>

        <!-- Formulaire pour ajouter un type de document -->
        <form id="addTypeForm" action="#" method="POST">
            <label for="name">Nom du type :</label>
            <input type="text" id="name" name="name" required>

            <h2>Champs associés</h2>
            
            <div id="fieldsContainer">
                <!-- Les champs dynamiques seront ajoutés par JavaScript -->
            </div>
            <button type="button" id="addField">Ajouter un champ</button>

            <br><br>
            <button type="submit">Ajouter le Type</button>
        </form>
    </div>
</body>
<script src="../assets/js/ajouter_types.js"></script>
</html>