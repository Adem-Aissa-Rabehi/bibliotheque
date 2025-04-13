<?php include 'SIde-barre.php'; ?>
<div class="container-fluid">
    <h1 class="mt-4">Ajouter un Document</h1>
    <form id="addDocumentForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="documentType">Type de document</label>
            <select class="form-control" id="documentType" name="document_type" required>
                <option value="">Sélectionnez un type</option>
                <option value="1">Mémoire</option>
                <option value="2">Livre</option>
            </select>
        </div>
        <div class="form-group">
            <label for="documentFile">Télécharger le document</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="documentFile" name="document_file" accept="application/pdf" required>
                <label class="custom-file-label" for="documentFile">Choisir un fichier</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter le document</button>
    </form>
</div>
<script src="../assets/js/ajouter_document.js"></script>