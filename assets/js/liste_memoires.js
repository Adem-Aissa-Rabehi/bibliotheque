document.addEventListener("DOMContentLoaded", function () {
    const memoiresTable = document.getElementById("memoiresTable");

    // Charger les mémoires
    const loadMemoires = () => {
        fetch("../assets/php/get_memoires.php")
            .then(response => response.json())
            .then(memoires => {
                memoiresTable.innerHTML = "";
                memoires.forEach(memoire => {
                    const row = `
                        <tr>
                            <td>${memoire.id}</td>
                            <td>${memoire.title}</td>
                            <td>${memoire.author}</td>
                            <td>${memoire.publication_date}</td>
                            <td>
                                <a href="../uploads/${memoire.document_path}" target="_blank" class="btn btn-info btn-sm">Voir</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${memoire.id}" data-path="../uploads/${memoire.document_path}">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    `;
                    memoiresTable.innerHTML += row;
                });
            })
            .catch(error => console.error("Erreur lors du chargement des mémoires :", error));
    };

    // Événements
    memoiresTable.addEventListener("click", (e) => {
        if (e.target.classList.contains("delete-btn")) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce mémoire ?")) {
                const memoireId = e.target.dataset.id;
                const memoirePath = e.target.dataset.path;

                fetch("../assets/php/delete_memoire.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `memoire_id=${encodeURIComponent(memoireId)}&memoire_path=${encodeURIComponent(memoirePath)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Mémoire supprimé avec succès !");
                            loadMemoires(); // Recharger les mémoires
                        } else {
                            alert("Erreur : " + data.message);
                        }
                    })
                    .catch(error => console.error("Erreur lors de la suppression :", error));
            }
        }
    });

    // Chargement initial
    loadMemoires();
});