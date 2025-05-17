document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const messageContainer = document.getElementById('messageContainer');
    const viewTypeModal = document.getElementById('viewTypeModal');
    const editTypeModal = document.getElementById('editTypeModal');
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const typeDetails = document.getElementById('typeDetails');
    const editTypeForm = document.getElementById('editTypeForm');
    const editTypeId = document.getElementById('editTypeId');
    const editTypeName = document.getElementById('editTypeName');
    const editFieldsContainer = document.getElementById('editFieldsContainer');
    const addEditFieldBtn = document.getElementById('addEditField');
    const deleteTypeName = document.getElementById('deleteTypeName');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    
    // Variables pour stocker les données
    let currentTypeId = null;
    let currentTypeFields = [];
    
    // Fonction pour afficher les messages
    function showMessage(message, type = 'info') {
        // Supprimer les messages existants
        const existingMessages = document.querySelectorAll('.message');
        existingMessages.forEach(msg => msg.remove());

        // Créer le nouveau message
        const messageElement = document.createElement('div');
        messageElement.className = `message ${type}-message`;
        messageElement.textContent = message;

        // Ajouter le message au début du contenu
        const content = document.querySelector('.content');
        content.insertBefore(messageElement, content.firstChild);

        // Supprimer le message après 3 secondes
        setTimeout(() => {
            messageElement.remove();
        }, 3000);
    }
    
    // Fonction pour fermer les modals
    function closeModals() {
        viewTypeModal.style.display = 'none';
        editTypeModal.style.display = 'none';
        deleteConfirmModal.style.display = 'none';
    }
    
    // Gestionnaires d'événements pour les boutons de fermeture des modals
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', closeModals);
    });
    
    // Fermer les modals en cliquant en dehors
    window.addEventListener('click', function(event) {
        if (event.target === viewTypeModal) {
            viewTypeModal.style.display = 'none';
        }
        if (event.target === editTypeModal) {
            editTypeModal.style.display = 'none';
        }
        if (event.target === deleteConfirmModal) {
            deleteConfirmModal.style.display = 'none';
        }
    });
    
    // Gestionnaire pour le bouton "Voir les détails"
    document.querySelectorAll('.view-type').forEach(button => {
        button.addEventListener('click', function() {
            const typeId = this.getAttribute('data-id');
            
            // Afficher un message de chargement
            typeDetails.innerHTML = '<p class="loading">Chargement des détails...</p>';
            viewTypeModal.style.display = 'block';
            
            // Récupérer les détails du type
            fetch(`assets/php/get_type_details.php?type_id=${typeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Afficher les détails du type
                        let html = `<h3>${data.type.nom}</h3>`;
                        
                        if (data.fields.length > 0) {
                            html += '<h4>Champs associés :</h4>';
                            html += '<ul class="fields-list">';
                            data.fields.forEach(field => {
                                html += `<li>
                                    <span class="field-name">${field.nom}</span>
                                    <span class="field-type">${field.type}</span>
                                </li>`;
                            });
                            html += '</ul>';
                        } else {
                            html += '<p>Aucun champ associé à ce type.</p>';
                        }
                        
                        typeDetails.innerHTML = html;
                    } else {
                        typeDetails.innerHTML = `<p class="error">Erreur : ${data.message}</p>`;
                    }
                })
                .catch(error => {
                    typeDetails.innerHTML = `<p class="error">Erreur lors de la récupération des détails : ${error.message}</p>`;
                });
        });
    });
    
    // Gestionnaire pour le bouton "Modifier"
    document.querySelectorAll('.edit-type').forEach(button => {
        button.addEventListener('click', function() {
            const typeId = this.getAttribute('data-id');
            currentTypeId = typeId;
            
            // Réinitialiser le formulaire
            editTypeForm.reset();
            editFieldsContainer.innerHTML = '';
            
            // Afficher un message de chargement
            editTypeName.value = 'Chargement...';
            editTypeModal.style.display = 'block';
            
            // Récupérer les détails du type
            fetch(`assets/php/get_type_details.php?type_id=${typeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remplir le formulaire avec les données du type
                        editTypeId.value = data.type.id;
                        editTypeName.value = data.type.nom;
                        
                        // Stocker les champs pour référence
                        currentTypeFields = data.fields;
                        
                        // Ajouter les champs existants
                        data.fields.forEach(field => {
                            addFieldToForm(field.id, field.nom, field.type);
                        });
                    } else {
                        showMessage(`Erreur : ${data.message}`, 'error');
                        editTypeModal.style.display = 'none';
                    }
                })
                .catch(error => {
                    showMessage(`Erreur lors de la récupération des détails : ${error.message}`, 'error');
                    editTypeModal.style.display = 'none';
                });
        });
    });
    
    // Fonction pour ajouter un champ au formulaire d'édition
    function addFieldToForm(fieldId = null, fieldName = '', fieldType = 'text') {
        const fieldGroup = document.createElement('div');
        fieldGroup.className = 'field-group';
        
        if (fieldId) {
            fieldGroup.setAttribute('data-field-id', fieldId);
        }
        
        fieldGroup.innerHTML = `
            <div class="field-container">
                <input type="text" class="field-name" placeholder="Nom du champ" value="${fieldName}" required>
            </div>
            <div class="field-container">
                <select class="field-type">
                    <option value="text" ${fieldType === 'text' ? 'selected' : ''}>Texte</option>
                    <option value="number" ${fieldType === 'number' ? 'selected' : ''}>Nombre</option>
                    <option value="date" ${fieldType === 'date' ? 'selected' : ''}>Date</option>
                    <option value="email" ${fieldType === 'email' ? 'selected' : ''}>Email</option>
                    <option value="url" ${fieldType === 'url' ? 'selected' : ''}>URL</option>
                </select>
            </div>
            <button type="button" class="delete-field-btn" title="Supprimer ce champ">
                <i class="fas fa-trash"></i>
            </button>
        `;
        
        // Ajouter le gestionnaire d'événements pour le bouton de suppression
        fieldGroup.querySelector('.delete-field-btn').addEventListener('click', function() {
            fieldGroup.remove();
        });
        
        editFieldsContainer.appendChild(fieldGroup);
    }
    
    // Gestionnaire pour le bouton "Ajouter un champ"
    addEditFieldBtn.addEventListener('click', function() {
        addFieldToForm();
    });
    
    // Gestionnaire pour la soumission du formulaire d'édition
    editTypeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Récupérer les données du formulaire
        const typeId = editTypeId.value;
        const typeName = editTypeName.value;
        
        // Récupérer les champs
        const fields = [];
        document.querySelectorAll('#editFieldsContainer .field-group').forEach(fieldGroup => {
            const fieldId = fieldGroup.getAttribute('data-field-id');
            const fieldName = fieldGroup.querySelector('.field-name').value;
            const fieldType = fieldGroup.querySelector('.field-type').value;
            
            fields.push({
                id: fieldId,
                nom: fieldName,
                type: fieldType
            });
        });
        
        // Afficher un message de chargement
        showMessage('Enregistrement des modifications...', 'info');
        
        // Envoyer les données au serveur
        fetch('assets/php/update_type.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type_id: typeId,
                nom: typeName,
                fields: fields
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Type de document mis à jour avec succès', 'success');
                editTypeModal.style.display = 'none';
                
                // Recharger la page après un court délai
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showMessage(`Erreur : ${data.message}`, 'error');
            }
        })
        .catch(error => {
            showMessage(`Erreur lors de la mise à jour : ${error.message}`, 'error');
        });
    });
    
    // Gestionnaire pour le bouton "Supprimer"
    document.querySelectorAll('.delete-type').forEach(button => {
        button.addEventListener('click', function() {
            const typeId = this.getAttribute('data-id');
            const typeName = this.getAttribute('data-name');
            
            currentTypeId = typeId;
            deleteTypeName.textContent = typeName;
            deleteConfirmModal.style.display = 'block';
        });
    });
    
    // Gestionnaire pour le bouton "Annuler" de la modal de confirmation
    cancelDeleteBtn.addEventListener('click', function() {
        deleteConfirmModal.style.display = 'none';
    });
    
    // Gestionnaire pour le bouton "Supprimer" de la modal de confirmation
    confirmDeleteBtn.addEventListener('click', function() {
        if (!currentTypeId) return;
        
        // Afficher un message de chargement
        showMessage('Suppression en cours...', 'info');
        
        // Envoyer la requête de suppression
        fetch('assets/php/delete_type.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                type_id: currentTypeId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Type de document supprimé avec succès', 'success');
                deleteConfirmModal.style.display = 'none';
                
                // Recharger la page après un court délai
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showMessage(`Erreur : ${data.message}`, 'error');
            }
        })
        .catch(error => {
            showMessage(`Erreur lors de la suppression : ${error.message}`, 'error');
        });
    });

    // Gérer la suppression des types
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const typeId = this.dataset.typeId;
            const typeName = this.dataset.typeName;

            if (confirm(`Êtes-vous sûr de vouloir supprimer le type "${typeName}" ? Cette action est irréversible.`)) {
                // Afficher un message de chargement
                showMessage('Suppression en cours...', 'info');

                // Envoyer la requête de suppression
                fetch('../assets/php/delete_type.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `type_id=${typeId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        // Supprimer la carte du type après un court délai
                        setTimeout(() => {
                            const typeCard = document.querySelector(`[data-type-id="${typeId}"]`).closest('.type-card');
                            typeCard.remove();
                            
                            // Vérifier s'il reste des types
                            const remainingTypes = document.querySelectorAll('.type-card');
                            if (remainingTypes.length === 0) {
                                const typesContainer = document.querySelector('.types-container');
                                typesContainer.innerHTML = '<div class="no-types">Aucun type de document n\'a été créé.</div>';
                            }
                        }, 1000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showMessage('Une erreur est survenue lors de la suppression.', 'error');
                });
            }
        });
    });
}); 