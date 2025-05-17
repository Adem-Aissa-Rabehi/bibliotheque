<?php
session_start();
require_once '../assets/php/logger.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$logger = new Logger();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher les Documents</title>
    <link rel="stylesheet" href="../assets/css/affdocuments.css">
    <link href="../assets/css/side-barre.css" rel="stylesheet">
</head>
<body id="body-pd">
    <?php include 'side-barre.php'; ?>
    <div class="container">
        <h1 class="text-center">Gestion des Documents</h1>
        
        <!-- Filtrage avancé -->
        <div class="filters-section">
            <h2>Filtres</h2>
            <div class="filter-group">
                <label for="typeSelect">Type de document :</label>
                <select id="typeSelect">
                    <option value="">Tous</option>
                </select>
                
                <label for="dateFrom">Date de publication (du) :</label>
                <input type="date" id="dateFrom">
                
                <label for="dateTo">Date de publication (au) :</label>
                <input type="date" id="dateTo">
                
                <label for="searchInput">Recherche :</label>
                <input type="text" id="searchInput" placeholder="Rechercher...">
            </div>
        </div>

        <!-- Tableau d'affichage -->
        <div class="table-container">
            <table id="documentsTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Titre</th>
                        <th>Date de publication</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les lignes des documents seront générées dynamiquement -->
                </tbody>
            </table>
        </div>

        <!-- Modal pour la modification -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modifier le Document</h2>
                <form id="editDocumentForm">
                    <input type="hidden" id="documentId" name="document_id">
                    
                    <div class="form-group">
                        <label for="documentType">Type de document</label>
                        <select id="documentType" name="document_type" required>
                            <!-- Options dynamiques à charger -->
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="documentTitle">Titre</label>
                        <input type="text" id="documentTitle" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="publicationDate">Date de publication</label>
                        <input type="date" id="publicationDate" name="publication_date" required>
                    </div>
                    
                    <div id="fieldsContainer">
                        <!-- Champs dynamiques selon le type de document -->
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Mettre à jour</button>
                        <button type="button" class="btn-secondary" onclick="closeModal()">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal pour la visualisation du PDF -->
        <div id="pdfModal" class="modal">
            <div class="modal-content pdf-viewer">
                <span class="close" onclick="closePdfModal()">&times;</span>
                <div class="pdf-container">
                    <iframe id="pdfFrame" src="" width="100%" height="100%" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/afficher_documents.js"></script>
    <script src="../assets/js/side-barre.js"></script>
    <script>
    // Add PDF viewer initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure PDF viewer is properly initialized
        const pdfFrame = document.getElementById('pdfFrame');
        if (pdfFrame) {
            pdfFrame.onload = function() {
                // Add any additional PDF viewer initialization here
            };
        }
    });
    </script>
</body>
</html>
