// Sélectionner les éléments du DOM
const addFieldButton = document.getElementById('addField');
const fieldsContainer = document.getElementById('fieldsContainer');

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
    deleteButton.addEventListener('click', () => fieldGroup.remove());

    // Ajouter les éléments au groupe
    fieldGroup.appendChild(fieldName);
    fieldGroup.appendChild(fieldType);
    fieldGroup.appendChild(deleteButton);

    // Ajouter le groupe au conteneur
    fieldsContainer.appendChild(fieldGroup);
});

// Envoi du formulaire via AJAX
document.getElementById("addTypeForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let fieldsContainer = document.getElementById("fieldsContainer");
    let formData = new FormData(this);

    // Vérifier si des champs ont été ajoutés
    let fields = fieldsContainer.querySelectorAll(".dynamic-field");

    if (fields.length === 0) {
        alert("Veuillez ajouter au moins un champ.");
        return;
    }

    // Validation et ajout des champs dynamiques au FormData
    let hasError = false;

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
        alert("Tous les champs doivent être correctement définis.");
        return;
    }

    // Envoi de la requête AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../assets/php/ajouter_type.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Type ajouté avec succès !");
                    document.getElementById("addTypeForm").reset();
                    fieldsContainer.innerHTML = ""; // Réinitialise les champs dynamiques
                } else {
                    alert("Erreur : " + response.message);
                }
            } catch (error) {
                console.error("Erreur de parsing JSON :", xhr.responseText);
                alert("Une erreur inattendue s'est produite.");
            }
        } else {
            alert("Une erreur est survenue.");
        }
    };
    
    xhr.send(formData);
});


