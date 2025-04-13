document.addEventListener("DOMContentLoaded", function () {
    const logsTable = document.getElementById("logsTable").querySelector("tbody");

    // Charger les logs via AJAX
    fetch("../assets/php/log_admin.php")
        .then(response => response.json())
        .then(logs => {
            logsTable.innerHTML = "";
            logs.forEach(log => {
                const row = `
                    <tr>
                        <td>${log.id}</td>
                        <td>${log.admin_name}</td>
                        <td>${log.actions}</td>
                        <td>${log.created_at}</td>
                    </tr>
                `;
                logsTable.innerHTML += row;
            });
        })
        .catch(error => console.error("Erreur lors du chargement des logs :", error));
});