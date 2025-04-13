<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
</head>
<body>
    <div class="login-container">
        <h2>Connexion Admin</h2>
        <form action="../assets/php/admin_login.php" method="POST">
            <div>
                <label for="name">Nom d'utilisateur</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>