<?php include 'SIde-barre.php'; ?>
<div class="container-fluid">
    <h1 class="mt-4">Ajouter un Type de Document</h1>
    <form id="addTypeForm" action="../assets/php/ajouter_type.php" method="POST">
        <div class="form-group">
            <label for="name">Nom du type :</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <h4 class="mt-4">Champs associés</h4>
        <div id="fieldsContainer"></div>

        <button type="button" id="addField" class="btn btn-secondary mb-3">Ajouter un champ</button>
        <button type="submit" class="btn btn-primary">Ajouter le Type</button>
    </form>
</div>

<!-- Scripts -->
<script src="../assets/js/ajouter_types.js"></script>