document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const editModal = document.getElementById("editModal");
    const editForm = document.getElementById("editDocumentForm");

    // Ouvrir le formulaire de modification
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            const documentId = this.dataset.id;
            fetch(`../assets/php/get_document_details.php?document_id=${documentId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("documentId").value = data.id;
                    document.getElementById("documentType").value = data.type_id;
                    document.getElementById("publicationDate").value = data.publication_date;

                    // Remplir les champs dynamiques
                    const fieldsContainer = document.getElementById("fieldsContainer");
                    fieldsContainer.innerHTML = "";
                    data.fields.forEach(field => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.innerHTML = `
                            <label>${field.name}</label>
                            <input type="text" name="fields[${field.id}]" value="${field.value}">
                        `;
                        fieldsContainer.appendChild(fieldGroup);
                    });

                    // Afficher le modal
                    editModal.style.display = "block";
                })
                .catch(error => console.error("Erreur lors du chargement des détails :", error));
        });
    });

    // Soumettre le formulaire de modification
    editForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../assets/php/update_document.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Document mis à jour avec succès !");
                    location.reload(); // Recharger la page pour afficher les modifications
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(error => console.error("Erreur lors de la mise à jour :", error));
    });

    // Fermer le modal
    document.querySelector("#editModal .close-btn").addEventListener("click", function () {
        editModal.style.display = "none";
    });
});