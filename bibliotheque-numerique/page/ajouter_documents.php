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
    <title>Ajouter un Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="../assets/css/side-barre.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
    <style>
        /* Main container layout */
        .main-container {
            display: flex;
            gap: 20px;
            padding: 20px;
            height: calc(100vh - 60px);
            margin: 10px 10px 0 220px;
            align-items: stretch;
        }

        .container {
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        .page-title {
            margin: 0 10px 10px 220px;
            padding: 15px;
        }

        /* Form container - smaller width */
        .form-container {
            flex: 0.4;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: 100%;
            overflow-y: auto;
            width: 40%;
            max-width: 40%;
        }

        /* Preview container - larger width */
        .preview-container {
            flex: 0.6;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 60%;
            max-width: 60%;
        }

        /* Iframe container adjustments */
        .iframe-container {
            flex: 1;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            min-height: 400px;
            margin-top: 1rem;
        }

        /* Form elements spacing */
        .mb-3 {
            margin-bottom: 1.5rem;
        }

        /* Make form inputs larger */
        .form-select, .form-control {
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
        }

        /* Preview title */
        .preview-container h5 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: #374151;
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .main-container {
                flex-direction: column;
            }
            .form-container,
            .preview-container {
                width: 100%;
                max-width: 100%;
            }
        }

        /* Document form styles */
        .iframe-container {
            flex: 1;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            min-height: 400px;
        }
        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            pointer-events: none;
        }
        .loading-spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .success-message {
            color: #198754;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        .file-info {
            margin-top: 10px;
            font-size: 0.875rem;
            color: #6c757d;
        }
        .preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: transparent;
            z-index: 5;
            pointer-events: auto;
        }
    </style>
</head>
<body id="body-pd">
    <?php include 'side-barre.php'; ?>
    <div class="container">
        <div class="page-title">
            <h1>Ajouter un Document</h1>
        </div>
        
        <!-- Messages d'alerte -->
        <div id="alertContainer" class="container"></div>

        <div class="main-container">
            <!-- Colonne gauche : Formulaire -->
            <div class="form-container">
                <form id="addDocumentForm" enctype="multipart/form-data">
                    <!-- Sélection du type de document -->
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Type de document</label>
                        <select id="documentType" name="document_type" class="form-select" required>
                            <option value="">Sélectionnez un type</option>
                        </select>
                        <div class="error-message" id="typeError"></div>
                    </div>
                    
                    <!-- Conteneur pour les champs dynamiques -->
                    <div id="fieldsContainer"></div>

                    <!-- Champ pour le fichier -->
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Télécharger le document</label>
                        <input type="file" id="documentFile" name="document_file" class="form-control" accept="application/pdf" required>
                        <div class="error-message" id="fileError"></div>
                        <div class="form-text">Formats acceptés : PDF (Max 10MB)</div>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="submitButton">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Ajouter le document
                        </button>
                    </div>
                </form>
            </div>

            <!-- Colonne droite : Prévisualisation -->
            <div class="preview-container">
                <h5 class="mb-3">Prévisualisation du document</h5>
                <div class="iframe-container">
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                    <div class="preview-overlay"></div>
                    <iframe id="documentPreview" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
                <div class="file-info" id="fileInfo"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
    <script src="../assets/js/side-barre.js"></script>
    <script src="../assets/js/ajouter_document.js"></script>
</body>
</html>
