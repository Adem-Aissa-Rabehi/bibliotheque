<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher les Documents</title>
    <link rel="stylesheet" href="../assets/css/affdocuments.css">
</head>
<body>
    <?php include 'SIde-barre.php'; ?>
    <div class="container">
        <h1 class="text-center">Tous les Documents</h1>
        <!-- Filtrage -->
        <div id="filters">
            <label for="typeSelect">Type de document :</label>
            <select id="typeSelect">
                <option value="">Tous</option>
            </select>
            <div id="fieldsFilters"></div>
        </div>
        <!-- Tableau d'affichage -->
        <table id="documentsTable">
    <thead>
        <tr>
            <th>Type</th>
            <th>Date de publication</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Les lignes des documents seront générées dynamiquement -->
    </tbody>
</table>

<!-- Formulaire pour la modification -->
<div id="editModal" style="display: none;">
    <form id="editDocumentForm">
        <input type="hidden" id="documentId" name="document_id">
        
        <label for="documentType">Type de document</label>
        <select id="documentType" name="document_type">
            <!-- Options dynamiques à charger -->
        </select>
        
        <label for="publicationDate">Date de publication</label>
        <input type="date" id="publicationDate" name="publication_date">
        
        <div id="fieldsContainer">
            <!-- Champs dynamiques -->
        </div>
        
        <button type="submit">Mettre à jour</button>
    </form>
</div>

    </div>
    <script src="../assets/js/afficher_documents.js"></script>
</body>
</html>
