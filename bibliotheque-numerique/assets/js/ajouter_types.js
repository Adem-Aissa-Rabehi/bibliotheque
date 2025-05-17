// Sélectionner les éléments du DOM
const addFieldButton = document.getElementById('addField');
const fieldsContainer = document.getElementById('fieldsContainer');
const addTypeForm = document.getElementById('addTypeForm');

// Fonction pour afficher les messages
function showMessage(message, type = 'info') {
    // Supprimer les messages existants
    const existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());
    
    // Créer un nouveau message
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    messageDiv.textContent = message;
    
    // Ajouter le message au début du formulaire
    addTypeForm.insertBefore(messageDiv, addTypeForm.firstChild);
    
    // Supprimer le message après 3 secondes
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Ajouter un champ dynamique
addFieldButton.addEventListener('click', () => {
    // Créer un groupe de champ
    const fieldGroup = document.createElement('div');
    fieldGroup.classList.add('field-group', 'dynamic-field'); // Ajout de la classe dynamique

    // Champ pour le nom
    const fieldName = document.createElement('input');
    fieldName.type = 'text';
    fieldName.name = 'field_name[]';
    fieldName.placeholder = 'Nom du champ';
    fieldName.classList.add('field-name'); // Classe pour identification
    fieldName.required = true;

    // Sélecteur pour le type de donnée
    const fieldType = document.createElement('select');
    fieldType.name = 'field_type[]';
    fieldType.classList.add('field-type'); // Classe pour identification
    fieldType.required = true;
    fieldType.innerHTML = `
        <option value="">Type de donnée</option>
        <option value="text">Texte</option>
        <option value="number">Nombre</option>
        <option value="date">Date</option>
        <option value="email">Email</option>
        <option value="textarea">Zone de texte</option>
    `;

    // Bouton pour supprimer ce champ
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Supprimer';
    deleteButton.classList.add('delete-field-btn');
    deleteButton.addEventListener('click', () => fieldGroup.remove());

    // Ajouter les éléments au groupe
    fieldGroup.appendChild(fieldName);
    fieldGroup.appendChild(fieldType);
    fieldGroup.appendChild(deleteButton);

    // Ajouter le groupe au conteneur
    fieldsContainer.appendChild(fieldGroup);
});

// Envoi du formulaire via AJAX
addTypeForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Vérifier si des champs ont été ajoutés
    let fields = fieldsContainer.querySelectorAll(".dynamic-field");

    if (fields.length === 0) {
        showMessage("Veuillez ajouter au moins un champ.", "error");
        return;
    }

    // Validation et ajout des champs dynamiques au FormData
    let hasError = false;
    let formData = new FormData(this);

    fields.forEach((field) => {
        let fieldName = field.querySelector(".field-name").value.trim();
        let fieldType = field.querySelector(".field-type").value.trim();

        if (!fieldName || !fieldType) {
            hasError = true;
        }

        formData.append(`field_names[]`, fieldName);
        formData.append(`field_types[]`, fieldType);
    });

    if (hasError) {
        showMessage("Tous les champs doivent être correctement définis.", "error");
        return;
    }

    // Afficher un message de chargement
    showMessage("Ajout du type en cours...", "info");

    // Envoi de la requête AJAX
    fetch("../assets/php/ajouter_type.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showMessage(data.message, "success");
            // Réinitialiser le formulaire après un court délai
            setTimeout(() => {
                addTypeForm.reset();
                fieldsContainer.innerHTML = ""; // Réinitialise les champs dynamiques
            }, 1500);
        } else {
            showMessage(data.message, "error");
        }
    })
    .catch(error => {
        console.error("Erreur:", error);
        showMessage("Une erreur est survenue lors de l'ajout du type.", "error");
    });
});


