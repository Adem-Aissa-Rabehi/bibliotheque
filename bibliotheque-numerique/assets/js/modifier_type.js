document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('modifierTypeForm');
    const addFieldBtn = document.getElementById('addField');
    const fieldsContainer = document.getElementById('fieldsContainer');
    const typeId = document.getElementById('typeId').value;
    const typeName = document.getElementById('typeName').value;

    // Fonction pour afficher les messages
    function showMessage(message, type = 'info') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        messageDiv.textContent = message;
        
        // Supprimer les messages existants
        const existingMessages = document.querySelectorAll('.message');
        existingMessages.forEach(msg => msg.remove());
        
        // Ajouter le nouveau message au début du formulaire
        form.insertBefore(messageDiv, form.firstChild);
        
        // Supprimer le message après 3 secondes
        setTimeout(() => messageDiv.remove(), 3000);
    }

    // Fonction pour créer un nouveau champ
    function createFieldGroup(name = '', type = 'text') {
        const fieldGroup = document.createElement('div');
        fieldGroup.className = 'field-group';
        
        fieldGroup.innerHTML = `
            <input type="text" class="field-name" placeholder="Nom du champ" value="${name}" required>
            <select class="field-type" required>
                <option value="text" ${type === 'text' ? 'selected' : ''}>Texte</option>
                <option value="number" ${type === 'number' ? 'selected' : ''}>Nombre</option>
                <option value="date" ${type === 'date' ? 'selected' : ''}>Date</option>
                <option value="file" ${type === 'file' ? 'selected' : ''}>Fichier</option>
            </select>
            <button type="button" class="delete-field">×</button>
        `;
        
        return fieldGroup;
    }

    // Charger les champs existants
    fetch(`../assets/php/get_fields.php?type_id=${typeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fields) {
                data.fields.forEach(field => {
                    const fieldGroup = createFieldGroup(field.name, field.field_type);
                    fieldsContainer.appendChild(fieldGroup);
                });
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des champs:', error);
            showMessage('Erreur lors du chargement des champs', 'error');
        });

    // Gérer l'ajout de nouveaux champs
    addFieldBtn.addEventListener('click', () => {
        const fieldGroup = createFieldGroup();
        fieldsContainer.appendChild(fieldGroup);
    });

    // Gérer la suppression des champs
    fieldsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('delete-field')) {
            e.target.parentElement.remove();
        }
    });

    // Gérer la soumission du formulaire
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Vérifier qu'il y a au moins un champ
        const fieldGroups = fieldsContainer.querySelectorAll('.field-group');
        if (fieldGroups.length === 0) {
            showMessage('Veuillez ajouter au moins un champ', 'error');
            return;
        }

        // Récupérer les données des champs
        const fields = Array.from(fieldGroups).map(group => ({
            name: group.querySelector('.field-name').value,
            type: group.querySelector('.field-type').value
        }));

        // Créer l'objet FormData
        const formData = new FormData();
        formData.append('typeId', typeId);
        formData.append('typeName', document.getElementById('typeName').value);
        formData.append('fields', JSON.stringify(fields));

        try {
            showMessage('Mise à jour en cours...', 'info');
            
            const response = await fetch('../assets/php/update_type.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    window.location.href = 'gestion_types.php';
                }, 1500);
            } else {
                showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Erreur lors de la mise à jour:', error);
            showMessage('Erreur lors de la mise à jour du type', 'error');
        }
    });
}); 