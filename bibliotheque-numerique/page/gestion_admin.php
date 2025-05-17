<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../assets/config/database.php';

// Check if user is logged in and is an admin
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
    <title>Gestion des Administrateurs</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-bottom: none;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }
        .tab.active {
            background-color: #fff;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
            font-weight: bold;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .logs-container {
            margin-top: 20px;
        }
        .log-entry {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .log-entry:last-child {
            border-bottom: none;
        }
        .log-date {
            color: #666;
            font-size: 0.9em;
        }
        .log-admin {
            font-weight: bold;
            color: #2c3e50;
        }
        .log-action {
            margin-top: 5px;
        }
        .search-box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'SIde-barre.php'; ?>
    
    <div class="container">
        <h1>Gestion des Administrateurs</h1>
        
        <div class="tabs">
            <div class="tab active" data-tab="admins">Administrateurs</div>
            <div class="tab" data-tab="logs">Logs d'administration</div>
        </div>
        
        <div class="tab-content active" id="admins-tab">
            <div class="admin-section">
                <h2>Ajouter un Administrateur</h2>
                <form id="addAdminForm">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <select id="role" name="role" required>
                            <option value="admin">Administrateur</option>
                            <option value="bibliothecaire">Bibliothécaire</option>
                            <option value="prof">Professeur</option>
                        </select>
                    </div>
                    <button type="submit">Ajouter</button>
                </form>
            </div>

            <div class="admin-section">
                <h2>Liste des Administrateurs</h2>
                <table id="adminTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminList">
                        <!-- Les administrateurs seront chargés ici dynamiquement -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-content" id="logs-tab">
            <div class="admin-section">
                <h2>Logs d'administration</h2>
                
                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher dans les logs...">
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Administrateur</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <!-- Les logs seront chargés ici dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin_management.js"></script>
    <script>
        // Fonction pour charger les logs
        function loadLogs() {
            fetch('../assets/php/get_admin_logs.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('logsTableBody');
                        tableBody.innerHTML = '';
                        
                        data.logs.forEach(log => {
                            const row = document.createElement('tr');
                            row.className = 'log-entry';
                            
                            const date = new Date(log.created_at);
                            const formattedDate = date.toLocaleString('fr-FR');
                            
                            row.innerHTML = `
                                <td class="log-date">${formattedDate}</td>
                                <td class="log-admin">${log.admin_name}</td>
                                <td class="log-action">${log.actions}</td>
                            `;
                            
                            tableBody.appendChild(row);
                        });
                    } else {
                        alert('Erreur lors du chargement des logs: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement des logs');
                });
        }

        // Fonction de recherche
        function searchLogs() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.getElementById('logsTableBody').getElementsByTagName('tr');
            
            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            }
        }

        // Gestion des onglets
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Désactiver tous les onglets
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Activer l'onglet cliqué
                    tab.classList.add('active');
                    document.getElementById(tab.dataset.tab + '-tab').classList.add('active');
                    
                    // Charger les logs si l'onglet des logs est sélectionné
                    if (tab.dataset.tab === 'logs') {
                        loadLogs();
                    }
                });
            });
            
            // Écouteur d'événements pour la recherche
            document.getElementById('searchInput').addEventListener('keyup', searchLogs);
        });
    </script>
</body>
</html> 