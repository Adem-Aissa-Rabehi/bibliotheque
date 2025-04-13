<?php include 'SIde-barre.php'; ?>
<div class="container-fluid">
    <h1 class="mt-4">Tous les Documents</h1>

    <!-- Filtrage -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="typeSelect">Type de document :</label>
            <select id="typeSelect" class="form-control">
                <option value="">Tous</option>
            </select>
        </div>
        <div class="col-md-9" id="fieldsFilters">
            <!-- Les filtres dynamiques seront chargés ici -->
        </div>
    </div>

    <!-- Tableau des documents -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Date de publication</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="documentsTable">
            <!-- Les lignes des documents seront générées dynamiquement -->
        </tbody>
    </table>
</div>

<!-- Scripts -->
<script src="../assets/js/afficher_documents.js"></script>