<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
require_once '../assets/php/db_connect.php';
require_once '../assets/php/check_auth.php';

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isAdmin()) {
    header('Location: login.php');
    exit();
}

// Récupérer tous les types de documents
try {
    error_log('Tentative de récupération des types');
    $stmt = $pdo->query("SELECT * FROM types ORDER BY name");
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log('Nombre de types trouvés: ' . count($types));
    error_log('Types: ' . print_r($types, true));
} catch (PDOException $e) {
    error_log('Erreur lors de la récupération des types: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Types - Bibliothèque Numérique</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-hover: #3651d4;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --success: #10b981;
            --success-hover: #059669;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg-hover: #f9fafb;
            --bg-light: #f3f4f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .search-container {
            position: relative;
            width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background-color: white;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            color: var(--text-light);
        }

        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        tr {
            border-bottom: 1px solid var(--border);
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: var(--bg-light);
            font-weight: 600;
            color: var(--text-dark);
            text-transform: uppercase;
            font-size: 0.75rem;
            border-bottom: 2px solid var(--border);
        }

        td {
            background: white;
        }

        tr:hover td {
            background: var(--bg-hover);
        }

        .fields-cell {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }

        .field-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            color: white;
            white-space: nowrap;
        }

        .field-tag[data-type="text"] { background-color: var(--primary); }
        .field-tag[data-type="number"] { background-color: var(--success); }
        .field-tag[data-type="date"] { background-color: #f59e0b; }
        .field-tag[data-type="textarea"] { background-color: #7c3aed; }
        .field-tag[data-type="file"] { background-color: var(--danger); }
        .field-tag[data-type="select"] { background-color: #06b6d4; }

        .actions-cell {
            text-align: right;
            white-space: nowrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 100px;
        }

        .btn + .btn {
            margin-left: 0.5rem;
        }

        .btn-modifier {
            background-color: var(--primary);
            color: white;
        }

        .btn-modifier:hover {
            background-color: var(--primary-hover);
        }

        .btn-supprimer {
            background-color: var(--danger);
            color: white;
        }

        .btn-supprimer:hover {
            background-color: var(--danger-hover);
        }

        .btn-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }

        /* Colonnes */
        th:first-child, td:first-child { width: 10%; }
        th:nth-child(2), td:nth-child(2) { width: 20%; }
        th:nth-child(3), td:nth-child(3) { width: 45%; }
        th:last-child, td:last-child { width: 25%; }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .search-container {
                width: 100%;
            }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 600px;
            margin: 2rem auto;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-light);
            cursor: pointer;
            padding: 0.5rem;
            margin: -0.5rem;
        }

        .close-modal:hover {
            color: var(--text-dark);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .fields-container {
            margin-top: 1rem;
        }

        .field-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.75rem;
            align-items: center;
        }

        .field-row .form-input {
            flex: 1;
        }

        .field-type-select {
            width: 150px;
        }

        .remove-field {
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            padding: 0.25rem;
        }

        .add-field {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--success);
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .add-field:hover {
            background: var(--success-hover);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Gérer les Types</h1>
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" class="search-input" placeholder="Rechercher un type...">
            </div>
        </div>
        
        <div class="card">
        <div class="table-container">
                <table>
                <thead>
                    <tr>
                        <th>ID</th>
                            <th>NOM</th>
                            <th>CHAMPS</th>
                            <th style="text-align: right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($types as $type): 
                            error_log('Type en cours de traitement: ' . print_r($type, true));
                            $stmt = $pdo->prepare("SELECT name, type as field_type FROM fields WHERE type_id = ? ORDER BY name");
                            $stmt->execute([$type['id']]);
                            $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                            <tr>
                                <td><?php echo $type['id']; ?></td>
                                <td><?php echo htmlspecialchars($type['name']); ?></td>
                                <td>
                                    <div class="fields-cell">
                                    <?php foreach ($fields as $field): ?>
                                        <span class="field-tag" data-type="<?php echo htmlspecialchars($field['field_type']); ?>">
                                            <?php echo htmlspecialchars($field['name']); ?>
                                        </span>
                                    <?php endforeach; ?>
                                    </div>
                                </td>
                                <td class="actions-cell">
                                    <button class="btn btn-modifier" onclick="modifierType(<?php echo $type['id']; ?>)">
                                        <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Modifier
                                    </button>
                                    <button class="btn btn-supprimer" onclick="supprimerType(<?php echo $type['id']; ?>)">
                                        <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    
    <!-- Modal de modification -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Modifier le type</h2>
                <button class="close-modal">&times;</button>
            </div>
            <form id="editForm">
                <input type="hidden" id="typeId" name="type_id">
                <div class="form-group">
                    <label class="form-label" for="typeName">Nom du type</label>
                    <input type="text" id="typeName" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Champs</label>
                    <div id="fieldsContainer" class="fields-container">
                    <!-- Les champs seront ajoutés ici dynamiquement -->
                    </div>
                    <button type="button" class="add-field" id="addField">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Ajouter un champ
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-supprimer" id="cancelEdit">Annuler</button>
                    <button type="submit" class="btn btn-modifier">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editModal');
            const editForm = document.getElementById('editForm');
            const fieldsContainer = document.getElementById('fieldsContainer');
            const addFieldBtn = document.getElementById('addField');
            const closeModal = modal.querySelector('.close-modal');
            const cancelEdit = document.getElementById('cancelEdit');

            // Fonction pour créer une ligne de champ
            function createFieldRow(name = '', type = 'text') {
                const row = document.createElement('div');
                row.className = 'field-row';
                row.innerHTML = `
                    <input type="text" class="form-input" name="field_names[]" placeholder="Nom du champ" value="${name}" required>
                    <select class="form-input field-type-select" name="field_types[]">
                        <option value="text" ${type === 'text' ? 'selected' : ''}>Texte</option>
                        <option value="number" ${type === 'number' ? 'selected' : ''}>Nombre</option>
                        <option value="date" ${type === 'date' ? 'selected' : ''}>Date</option>
                        <option value="textarea" ${type === 'textarea' ? 'selected' : ''}>Zone de texte</option>
                        <option value="file" ${type === 'file' ? 'selected' : ''}>Fichier</option>
                        <option value="select" ${type === 'select' ? 'selected' : ''}>Liste déroulante</option>
                    </select>
                    <button type="button" class="remove-field">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                `;
                return row;
            }

            // Fonction pour modifier un type
            window.modifierType = function(typeId) {
                console.log('Modification du type:', typeId);
                
                // Récupérer les détails du type
                fetch(`../assets/php/get_type_details.php?id=${typeId}`)
                    .then(response => {
                        console.log('Statut de la réponse:', response.status);
                        if (!response.ok) {
                            throw new Error(`Erreur HTTP: ${response.status}`);
                        }
                        return response.text();
                    })
                    .then(text => {
                        console.log('Texte brut reçu:', text);
                        if (!text) {
                            throw new Error('Réponse vide du serveur');
                        }
                        try {
                            const data = JSON.parse(text);
                            console.log('JSON parsé:', data);
                            return data;
                        } catch (e) {
                            console.error('Erreur de parsing JSON:', e);
                            throw new Error('Réponse invalide du serveur');
                        }
                    })
                    .then(data => {
                        console.log('Traitement des données:', data);
                        if (!data || !data.success) {
                            throw new Error(data?.message || 'Erreur lors de la récupération des données');
                        }
                        if (!data.type) {
                            throw new Error('Les données du type sont manquantes');
                        }

                        // Remplir le formulaire
                        document.getElementById('typeId').value = typeId;
                        document.getElementById('typeName').value = data.type.name || '';
                        
                        // Vider et remplir le conteneur de champs
                        fieldsContainer.innerHTML = '';
                        if (data.type.fields && Array.isArray(data.type.fields)) {
                            console.log('Champs trouvés:', data.type.fields.length);
                            data.type.fields.forEach((field, index) => {
                                console.log(`Champ ${index}:`, field);
                                fieldsContainer.appendChild(createFieldRow(field.name, field.field_type));
                            });
                        } else {
                            console.log('Aucun champ trouvé');
                        }
                        
                        // Afficher le modal
                        modal.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Erreur complète:', error);
                        alert('Une erreur est survenue: ' + error.message);
                    });
            };

            // Fonction pour supprimer un type
            window.supprimerType = function(typeId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce type ?')) {
                    const formData = new FormData();
                    formData.append('type_id', typeId);
                    
                    fetch('../assets/php/delete_type.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Recharger la page après la suppression
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue lors de la suppression');
                    });
                }
            };

            // Ajouter un nouveau champ
            addFieldBtn.addEventListener('click', function() {
                fieldsContainer.appendChild(createFieldRow());
            });

            // Supprimer un champ
            fieldsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-field')) {
                    e.target.closest('.field-row').remove();
                }
            });

            // Fermer le modal
            function closeModalHandler() {
                modal.style.display = 'none';
                editForm.reset();
                fieldsContainer.innerHTML = '';
            }

            closeModal.addEventListener('click', closeModalHandler);
            cancelEdit.addEventListener('click', closeModalHandler);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalHandler();
                }
            });

            // Soumettre le formulaire
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Soumission du formulaire');
                
                const formData = new FormData(this);
                
                // Log des données du formulaire
                console.log('Type ID:', formData.get('type_id'));
                console.log('Nom:', formData.get('name'));
                console.log('Noms des champs:', formData.getAll('field_names[]'));
                console.log('Types des champs:', formData.getAll('field_types[]'));

                // Vérifier que les données sont présentes
                if (!formData.get('type_id') || !formData.get('name')) {
                    alert('Veuillez remplir tous les champs obligatoires');
                    return;
                }
                
                fetch('../assets/php/update_type.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Statut de la réponse:', response.status);
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Texte reçu:', text);
                    if (!text) {
                        throw new Error('Réponse vide du serveur');
                    }
                    try {
                        const data = JSON.parse(text);
                        console.log('Réponse parsée:', data);
                        if (data.success) {
                            // Fermer le modal
                            modal.style.display = 'none';
                            // Recharger la page
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Erreur lors de la modification');
                        }
                    } catch (e) {
                        console.error('Erreur de parsing JSON:', e);
                        throw new Error('Réponse invalide du serveur');
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la modification:', error);
                    alert('Une erreur est survenue: ' + error.message);
                });
            });

            // Recherche
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html> 