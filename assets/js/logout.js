document.addEventListener("DOMContentLoaded", function () {
    const logoutButton = document.getElementById("logoutButton");

    logoutButton.addEventListener("click", function () {
        fetch("../assets/php/logout.php", {
            method: "POST"
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Déconnexion réussie !");
                    window.location.href = "../page/login.php"; // Redirection vers la page de connexion
                } else {
                    alert("Erreur lors de la déconnexion.");
                }
            })
            .catch(error => console.error("Erreur lors de la déconnexion :", error));
    });
});