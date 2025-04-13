<?php
// Barre latérale Bootstrap 4
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #007bff;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Barre latérale -->
    <div class="sidebar">
        <h3 class="text-center mb-4">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ajouter_documents.php"><i class="fas fa-file-upload mr-2"></i> Ajouter Document</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="liste_memoires.php"><i class="fas fa-book mr-2"></i> Liste des Mémoires</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logs.php"><i class="fas fa-history mr-2"></i> Logs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.php"><i class="fas fa-cog mr-2"></i> Paramètres</a>
            </li>
        </ul>
    </div>

    <!-- Contenu principal -->
    <div class="main-content">
        <?php include 'content.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>