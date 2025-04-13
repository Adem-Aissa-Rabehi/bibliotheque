<?php include 'SIde-barre.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Mémoires</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <h1 class="mt-4">Liste des Mémoires</h1>

        <!-- Tableau des mémoires -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Date de publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="memoiresTable">
                <!-- Les lignes des mémoires seront générées dynamiquement -->
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/liste_memoires.js"></script>
</body>
</html>