$logged = $_SESSION['admin_id'];
        if ($logged) {
            $action = "Nouveau type de document ajouté: $name ";
            $sql = "INSERT INTO admin_logs (admin_id, actions , created_at) VALUES (:admin_id, :actions , :dates)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $logged,
                ':action' => $action,
                ':dates' => time()
            ]);
        }