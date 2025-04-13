<?php include 'SIde-barre.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="main-content">
        <div class="container-fluid">
            <h1 class="mt-4">Paramètres</h1>
            <p>Gérez vos préférences ici.</p>

            <!-- Formulaire de paramètres -->
            <form id="settingsForm">
                <div class="form-group">
                    <label for="theme">Thème :</label>
                    <select class="form-control" id="theme" name="theme">
                        <option value="light">Clair</option>
                        <option value="dark">Sombre</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sauvegarder les paramètres via AJAX
        document.getElementById("settingsForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("../assets/php/save_settings.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Paramètres enregistrés avec succès !");
                    } else {
                        alert("Erreur : " + data.message);
                    }
                })
                .catch(error => console.error("Erreur lors de l'enregistrement des paramètres :", error));
        });
    </script>
</body>
</html>