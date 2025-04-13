document.addEventListener("DOMContentLoaded", function () {
    const documentType = document.getElementById("documentType");
    const fieldsContainer = document.getElementById("fieldsContainer");
    const documentFile = document.getElementById("documentFile");
    const documentPreview = document.getElementById("documentPreview");

    // Charger les types via AJAX
    const loadDocumentTypes = () => {
        fetch("../assets/php/get_types.php")
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    documentType.innerHTML = '<option value="">Sélectionner un type</option>';
                    data.types.forEach(type => {
                        const option = document.createElement("option");
                        option.value = type.id;
                        option.textContent = type.name;
                        documentType.appendChild(option);
                    });
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => console.error("Erreur lors du chargement des types :", error));
    };

    // Charger les champs dynamiques en fonction du type sélectionné
    documentType.addEventListener("change", () => {
        const typeId = documentType.value;
        fieldsContainer.innerHTML = ""; // Réinitialiser les champs dynamiques

        if (!typeId) return;

        fetch(`../assets/php/get_fields.php?type_id=${typeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    data.fields.forEach(field => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.classList.add("mb-3");

                        // Label du champ
                        const label = document.createElement("label");
                        label.classList.add("form-label");
                        label.textContent = field.name;

                        // Input ou Textarea en fonction du type
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

                        fieldGroup.appendChild(label);
                        fieldGroup.appendChild(input);
                        fieldsContainer.appendChild(fieldGroup);
                    });
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => console.error("Erreur lors du chargement des champs :", error));
    });

    // Prévisualisation du fichier PDF
    documentFile.addEventListener("change", () => {
        const file = documentFile.files[0];
        if (file && file.type === "application/pdf") {
            const fileURL = URL.createObjectURL(file);
            documentPreview.src = fileURL;
        } else {
            alert("Veuillez sélectionner un fichier PDF valide.");
            documentFile.value = "";
            documentPreview.src = "";
        }
    });

    // Soumission du formulaire
    document.getElementById("addDocumentForm").addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        fetch("../assets/php/ajouter_document.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Document ajouté avec succès !");
                    e.target.reset();
                    fieldsContainer.innerHTML = "";
                    documentPreview.src = "";
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => console.error("Erreur lors de l'ajout du document :", error));
    });

    // Chargement initial
    loadDocumentTypes();
});