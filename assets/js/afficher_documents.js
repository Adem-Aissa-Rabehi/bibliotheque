document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("typeSelect");
    const fieldsFilters = document.getElementById("fieldsFilters");
    const documentsTable = document.getElementById("documentsTable").querySelector("tbody");

    // Charger les types de documents
    const loadTypes = () => {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../assets/php/get_document_types.php", true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const types = JSON.parse(xhr.responseText);
                types.forEach(type => {
                    const option = document.createElement("option");
                    option.value = type.id;
                    option.textContent = type.name;
                    typeSelect.appendChild(option);
                });
            }
        };
        xhr.send();
    };

    // Charger les documents
    const loadDocuments = (typeId = "", filters = {}) => {
        const xhr = new XMLHttpRequest();
        let url = `../assets/php/get_document.php?type=${typeId}`;
        Object.keys(filters).forEach(key => {
            url += `&${key}=${filters[key]}`;
        });

        xhr.open("GET", url, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const documents = JSON.parse(xhr.responseText);
                documentsTable.innerHTML = "";
                documents.forEach(doc => {
                    const row = `<tr>
                        <td>${doc.id}</td>
                        <td>${doc.type}</td>
                        <td>${doc.publication_date}</td>
                        <td><a href="../uploads/${doc.document_path}" target="_blank">Voir</a></td>
                        <td>
                            <button class="delete-btn" data-id="${doc.id}" data-path="../uploads/${doc.document_path}">Supprimer</button>
                        </td>
                    </tr>`;
                    documentsTable.innerHTML += row;
                });
            }
        };
        xhr.send();
    };

    // Charger les champs dynamiques
    const loadFields = (typeId) => {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../assets/php/get_document_fields.php?type=${typeId}`, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const fields = JSON.parse(xhr.responseText);
                fieldsFilters.innerHTML = ""; // Clear previous filters
                fields.forEach(field => {
                    const filter = document.createElement("div");
                    filter.innerHTML = `
                        <label for="field_${field.id}">${field.name}</label>
                        <select id="field_${field.id}" data-field-id="${field.id}" >
                            <option value="">Tous</option>
                        </select>
                    `;
                    fieldsFilters.appendChild(filter);

                    // Charger les valeurs pour chaque champ
                    loadFieldValues(field.id);
                });
            }
        };
        xhr.send();
    };

    // Charger les valeurs d'un champ
    const loadFieldValues = (fieldId) => {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `../assets/php/get_field_values.php?field=${fieldId}`, true);
        xhr.onload = () => {
            if (xhr.status === 200) {
                const values = JSON.parse(xhr.responseText);
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
            }
        };
        xhr.send();
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

    // Chargement initial
    loadTypes();
    loadDocuments();
});





document.addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-btn')) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
            const documentId = e.target.dataset.id;
            const documentPath = e.target.dataset.path;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../assets/php/delete_document.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);

                        if (response.success) {
                            location.reload(); // Actualise le tableau
                        }
                    } catch (error) {
                        alert("Erreur de réponse du serveur.");
                        console.error("Erreur JSON :", error, xhr.responseText);
                    }
                }
            };
            xhr.send(`document_id=${encodeURIComponent(documentId)}&document_path=${encodeURIComponent(documentPath)}`);
        }
    }
});


