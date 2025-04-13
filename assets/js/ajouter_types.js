document.addEventListener("DOMContentLoaded", function () {
    const addFieldButton = document.getElementById("addField");
    const fieldsContainer = document.getElementById("fieldsContainer");

    // Ajouter un champ dynamique
    addFieldButton.addEventListener("click", () => {
        const fieldGroup = document.createElement("div");
        fieldGroup.classList.add("field-group", "dynamic-field");

        // Champ pour le nom
        const fieldName = document.createElement("input");
        fieldName.type = "text";
        fieldName.name = "field_name[]";
        fieldName.placeholder = "Nom du champ";
        fieldName.classList.add("form-control");
        fieldName.required = true;

        // Sélecteur pour le type de donnée
        const fieldType = document.createElement("select");
        fieldType.name = "field_type[]";
        fieldType.classList.add("form-control");
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
        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.textContent = "Supprimer";
        deleteButton.classList.add("btn", "btn-danger", "mt-2");
        deleteButton.addEventListener("click", () => fieldGroup.remove());

        fieldGroup.appendChild(fieldName);
        fieldGroup.appendChild(fieldType);
        fieldGroup.appendChild(deleteButton);
        fieldsContainer.appendChild(fieldGroup);
    });

    // Soumission du formulaire
    document.getElementById("addTypeForm").addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const fields = fieldsContainer.querySelectorAll(".dynamic-field");

        if (fields.length === 0) {
            alert("Veuillez ajouter au moins un champ.");
            return;
        }

        let hasError = false;
        fields.forEach(field => {
            const fieldName = field.querySelector("input").value.trim();
            const fieldType = field.querySelector("select").value.trim();

            if (!fieldName || !fieldType) {
                hasError = true;
            }

            formData.append("field_names[]", fieldName);
            formData.append("field_types[]", fieldType);
        });

        if (hasError) {
            alert("Tous les champs doivent être correctement définis.");
            return;
        }

        fetch("../assets/php/ajouter_type.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Type ajouté avec succès !");
                    e.target.reset();
                    fieldsContainer.innerHTML = "";
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => console.error("Erreur lors de l'ajout du type :", error));
    });
});