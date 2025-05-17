document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addDocumentForm");
    const selectElement = document.getElementById("documentType");
    const fileInput = document.getElementById("documentFile");
    const submitButton = document.getElementById("submitButton");
    const spinner = submitButton.querySelector(".spinner-border");
    const alertContainer = document.getElementById("alertContainer");
    const fileInfo = document.getElementById("fileInfo");
    const typeError = document.getElementById("typeError");
    const fileError = document.getElementById("fileError");

    // Fonction pour afficher les messages d'alerte
    function showAlert(message, type = 'success') {
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
    }

    // Fonction pour afficher les erreurs de champ
    function showFieldError(element, message) {
        element.classList.add('is-invalid');
        const errorDiv = element.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    // Fonction pour effacer les erreurs de champ
    function clearFieldError(element) {
        element.classList.remove('is-invalid');
        const errorDiv = element.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.style.display = 'none';
        }
    }

    // Charger les types de documents
    function loadDocumentTypes() {
        fetch("../assets/php/get_types.php")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez un type</option>';
                    data.types.forEach(type => {
                        const option = document.createElement("option");
                        option.value = type.id;
                        option.textContent = type.name;
                        selectElement.appendChild(option);
                    });
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('Une erreur est survenue lors du chargement des types.', 'danger');
            });
    }

    // Charger les champs dynamiques
    function loadFields(typeId) {
        const fieldsContainer = document.getElementById("fieldsContainer");
        fieldsContainer.innerHTML = '';

        if (!typeId) return;

        fetch(`../assets/php/get_fields.php?type_id=${typeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.fields.forEach(field => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.classList.add("mb-3");

                        const label = document.createElement("label");
                        label.classList.add("form-label");
                        label.textContent = field.name;

                        let input;
                        if (field.type === "textarea") {
                            input = document.createElement("textarea");
                            input.classList.add("form-control");
                            input.rows = 4;
                        } else {
                            input = document.createElement("input");
                            input.classList.add("form-control");
                            input.type = field.type;
                        }

                        input.name = `fields[${field.id}]`;
                        input.required = true;

                        const errorDiv = document.createElement("div");
                        errorDiv.classList.add("error-message");

                        fieldGroup.appendChild(label);
                        fieldGroup.appendChild(input);
                        fieldGroup.appendChild(errorDiv);
                        fieldsContainer.appendChild(fieldGroup);
                    });
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('Une erreur est survenue lors du chargement des champs.', 'danger');
            });
    }

    // Gestionnaire d'événement pour le changement de type
    selectElement.addEventListener("change", function () {
        clearFieldError(this);
        loadFields(this.value);
    });

    // Gestionnaire d'événement pour le changement de fichier
    fileInput.addEventListener("change", function () {
        clearFieldError(this);
        const file = this.files[0];
        
        if (file) {
            if (file.type !== "application/pdf") {
                showFieldError(this, "Veuillez sélectionner un fichier PDF valide.");
                this.value = "";
                return;
            }

            if (file.size > 10 * 1024 * 1024) { // 10MB
                showFieldError(this, "Le fichier est trop volumineux. Taille maximum : 10MB");
                this.value = "";
                return;
            }

            fileInfo.textContent = `Fichier sélectionné : ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            
            // Afficher le spinner pendant le chargement
            document.querySelector('.loading-spinner').style.display = 'block';
            
            const fileURL = URL.createObjectURL(file);
            const iframe = document.getElementById("documentPreview");
            
            // Utiliser l'URL du fichier directement
            iframe.src = fileURL;
            
            // Masquer le spinner une fois le PDF chargé
            iframe.onload = function() {
                document.querySelector('.loading-spinner').style.display = 'none';
            };
        } else {
            fileInfo.textContent = "";
            document.getElementById("documentPreview").src = "";
        }
    });

    // Gestionnaire d'événement pour la soumission du formulaire
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        
        // Validation du type
        if (!selectElement.value) {
            showFieldError(selectElement, "Veuillez sélectionner un type de document");
            return;
        }

        // Validation du fichier
        if (!fileInput.files[0]) {
            showFieldError(fileInput, "Veuillez sélectionner un fichier");
            return;
        }

        // Désactiver le bouton et afficher le spinner
        submitButton.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(this);

        fetch("../assets/php/ajouter_document.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("Document ajouté avec succès !");
                form.reset();
                document.getElementById("documentPreview").src = "";
                fileInfo.textContent = "";
                loadFields(selectElement.value);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Une erreur est survenue lors de l\'ajout du document.', 'danger');
        })
        .finally(() => {
            // Réactiver le bouton et cacher le spinner
            submitButton.disabled = false;
            spinner.classList.add('d-none');
        });
    });

    // Charger les types au chargement de la page
    loadDocumentTypes();
});