document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("typeSelect");
    const fieldsFilters = document.getElementById("fieldsFilters");
    const documentsTable = document.getElementById("documentsTable").querySelector("tbody");

    // Charger les types de documents
    const loadTypes = () => {
        fetch("../assets/php/get_document_types.php")
            .then(response => response.json())
            .then(types => {
                types.forEach(type => {
                    const option = document.createElement("option");
                    option.value = type.id;
                    option.textContent = type.name;
                    typeSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erreur lors du chargement des types :", error));
    };

    // Charger les documents
    const loadDocuments = (typeId = "", filters = {}) => {
        let url = `../assets/php/get_document.php?type=${typeId}`;
        Object.keys(filters).forEach(key => {
            url += `&${key}=${filters[key]}`;
        });

        fetch(url)
            .then(response => response.json())
            .then(documents => {
                documentsTable.innerHTML = "";
                documents.forEach(doc => {
                    const row = `
                        <tr>
                            <td>${doc.type}</td>
                            <td>${doc.publication_date}</td>
                            <td>
                                <a href="../uploads/${doc.document_path}" target="_blank">Voir</a>
                            </td>
                            <td>
                                <button class="btn btn-danger delete-btn" data-id="${doc.id}" data-path="../uploads/${doc.document_path}">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    `;
                    documentsTable.innerHTML += row;
                });
            })
            .catch(error => console.error("Erreur lors du chargement des documents :", error));
    };

    // Charger les champs dynamiques
    const loadFields = (typeId) => {
        fetch(`../assets/php/get_document_fields.php?type=${typeId}`)
            .then(response => response.json())
            .then(fields => {
                fieldsFilters.innerHTML = ""; // Effacer les filtres précédents
                fields.forEach(field => {
                    const filter = document.createElement("div");
                    filter.innerHTML = `
                        <label for="field_${field.id}">${field.name}</label>
                        <select id="field_${field.id}" data-field-id="${field.id}">
                            <option value="">Tous</option>
                        </select>
                    `;
                    fieldsFilters.appendChild(filter);

                    // Charger les valeurs pour chaque champ
                    loadFieldValues(field.id);
                });
            })
            .catch(error => console.error("Erreur lors du chargement des champs :", error));
    };

    // Charger les valeurs d'un champ
    const loadFieldValues = (fieldId) => {
        fetch(`../assets/php/get_field_values.php?field=${fieldId}`)
            .then(response => response.json())
            .then(values => {
                const select = document.querySelector(`#field_${fieldId}`);
                values.forEach(value => {
                    const option = document.createElement("option");
                    option.value = value;
                    option.textContent = value;
                    select.appendChild(option);
                });

                // Ajouter un écouteur d'événement pour filtrer
                select.addEventListener("change", () => {
                    const filters = {};
                    document.querySelectorAll("#fieldsFilters select").forEach(select => {
                        const fieldId = select.getAttribute("data-field-id");
                        const value = select.value;
                        if (value) filters[`field_${fieldId}`] = value;
                    });
                    loadDocuments(typeSelect.value, filters);
                });
            })
            .catch(error => console.error("Erreur lors du chargement des valeurs :", error));
    };

    // Événements
    typeSelect.addEventListener("change", () => {
        const typeId = typeSelect.value;
        loadDocuments(typeId);
        if (typeId) {
            loadFields(typeId);
        } else {
            fieldsFilters.innerHTML = "";
        }
    });

    // Gestion de la suppression
    documentsTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-btn")) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce document ?")) {
                const documentId = e.target.dataset.id;
                const documentPath = e.target.dataset.path;

                fetch("../assets/php/delete_document.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `document_id=${encodeURIComponent(documentId)}&document_path=${encodeURIComponent(documentPath)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Document supprimé avec succès !");
                            loadDocuments(typeSelect.value); // Recharger les documents
                        } else {
                            alert("Erreur : " + data.message);
                        }
                    })
                    .catch(error => console.error("Erreur lors de la suppression :", error));
            }
        }
    });

    // Chargement initial
    loadTypes();
    loadDocuments();
});