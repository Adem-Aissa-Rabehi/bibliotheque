<?php include 'SIde-barre.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .card {
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container-fluid">
            <h1 class="mt-4">Tableau de Bord</h1>
            <p>Bienvenue sur votre tableau de bord administrateur.</p>

            <!-- Widgets -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Documents</h5>
                            <p class="card-text" id="documentCount">Chargement...</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Types</h5>
                            <p class="card-text" id="typeCount">Chargement...</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Administrateurs</h5>
                            <p class="card-text" id="adminCount">Chargement...</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title">Logs</h5>
                            <p class="card-text" id="logCount">Chargement...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Charger les statistiques via AJAX
        document.addEventListener("DOMContentLoaded", function () {
            fetch("../assets/php/dashboard_stats.php")
                .then(response => response.json())
                .then(data => {
                    document.getElementById("documentCount").textContent = data.documents || "0";
                    document.getElementById("typeCount").textContent = data.types || "0";
                    document.getElementById("adminCount").textContent = data.admins || "0";
                    document.getElementById("logCount").textContent = data.logs || "0";
                })
                .catch(error => console.error("Erreur lors du chargement des statistiques :", error));
        });
    </script>
</body>
</html>