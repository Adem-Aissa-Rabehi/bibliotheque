document.addEventListener("DOMContentLoaded", function () {
    const selectElement = document.getElementById("documentType");

    // Charger les types via AJAX
    function loadDocumentTypes() {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../assets/php/get_types.php", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Effacer les options existantes
                    selectElement.innerHTML = '<option value="">Sélectionner un type</option>';
                    
                    // Ajouter les options dynamiques
                    response.types.forEach(type => {
                        const option = document.createElement("option");
                        option.value = type.id;
                        option.textContent = type.name;
                        selectElement.appendChild(option);
                    });
                } else {
                    alert("Erreur : " + response.message);
                }
            } else {
                alert("Une erreur est survenue lors du chargement des types.");
            }
        };
        xhr.send();
    }

    loadDocumentTypes();
});
const fieldsContainer = document.getElementById("fieldsContainer");

// Gestionnaire d'événement pour la liste déroulante du type de document
document.getElementById("documentType").addEventListener("change", function () {
    const typeId = this.value; // ID du type sélectionné
    fieldsContainer.innerHTML = ""; // Réinitialise les champs dynamiques

    if (!typeId) {
        return; // Si aucun type sélectionné, on arrête
    }

    // Requête AJAX pour récupérer les champs associés au type sélectionné
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `../assets/php/get_fields.php?type_id=${typeId}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);

            if (response.success) {
                // Générer un champ pour chaque field récupéré
                response.fields.forEach(field => {
                    const fieldGroup = document.createElement("div");
                    fieldGroup.classList.add("mb-3");

                    // Label du champ
                    const label = document.createElement("label");
                    label.classList.add("form-label");
                    label.textContent = field.name;

                    // Générer l'input en fonction du type
                    let input;
                    if (field.type === "textarea") {
                        input = document.createElement("textarea");
                        input.classList.add("form-control");
                        input.rows = 4; // Nombre de lignes par défaut
                    } else {
                        input = document.createElement("input");
                        input.classList.add("form-control");
                        input.type = field.type; // Applique le type du champ (ex: text, number, date)
                    }

                    input.name = `fields[${field.id}]`; // Nom unique pour chaque champ (lié à l'ID de la table `fields`)
                    input.required = true; // Champ obligatoire

                    // Ajouter le label et l'input au conteneur
                    fieldGroup.appendChild(label);
                    fieldGroup.appendChild(input);

                    // Ajouter le groupe au conteneur principal
                    fieldsContainer.appendChild(fieldGroup);
                });
            } else {
                alert("Erreur : " + response.message);
            }
        } else {
            alert("Erreur lors du chargement des champs.");
        }
    };
    xhr.send();
});
document.getElementById("documentFile").addEventListener("change", function () {
    const file = this.files[0];

    if (file && file.type === "application/pdf") {
        const fileURL = URL.createObjectURL(file);
        const iframe = document.getElementById("documentPreview");
        iframe.src = fileURL;
    } else {
        alert("Veuillez sélectionner un fichier PDF valide.");
        this.value = "";
        document.getElementById("documentPreview").src = "";
    }
});
document.getElementById("addDocumentForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    
    // Ajouter les champs dynamiques au formData
    document.querySelectorAll(".dynamic-field").forEach(field => {
        let fieldId = field.getAttribute("data-field-id");
        let fieldValue = field.querySelector("input, select, textarea").value;
        formData.append(`fields[${fieldId}]`, fieldValue);
    });

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../assets/php/ajouter_document.php", true);
    xhr.onload = function () {
        let response = JSON.parse(xhr.responseText);
        if (response.success) {
            alert("Document ajouté avec succès !");
            document.getElementById("addDocumentForm").reset();
            document.getElementById("fieldsContainer").innerHTML = "";
        } else {
            alert("Erreur : " + response.message);
        }
    };
    xhr.send(formData);
});