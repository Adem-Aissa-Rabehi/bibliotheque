
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Document</title>
    <link rel="stylesheet" href="../assets/css/documents.css">
  
</head>
<?php include 'SIde-barre.php'; ?>
<body>
    <div class="container ">
        <h1 class="text-center ">Ajouter un Document</h1>
        <div class="row">
            <!-- Colonne gauche : Prévisualisation -->
            <div class="col">
            <div class="preview-container">
                <h5>Prévisualisation du document</h5>
                <div class="iframe-container">
                    <iframe id="documentPreview" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>

            </div>
            </div>
            
            <!-- Colonne droite : Formulaire -->
            <div class="col">
                <form id="addDocumentForm" enctype="multipart/form-data">
                    <!-- Sélection du type de document -->
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Type de document</label>
                        <select id="documentType" name="document_type" class="form-select" required>
                            <option value="">Sélectionnez un type</option>
                            <!-- Options chargées dynamiquement depuis la BDD -->
                        </select>
                    </div>
                    
                    <!-- Conteneur pour les champs dynamiques -->
                    <div id="fieldsContainer"></div>

                    <!-- Champ pour le fichier -->
                    <div class="mb">
                        <label for="documentFile" class="form-label">Télécharger le document</label>
                        <input type="file" id="documentFile" name="document_file" class="form-control" accept="application/pdf" required>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn">Ajouter le document</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/ajouter_document.js"></script>
</body>
</html>
