<?php
require_once 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = getDatabaseConnection();
    
    // Récupérer les données du formulaire
    $typeId = $_POST['document_type'] ?? null;
    $fields = $_POST['fields'] ?? [];

    // Vérifier si un fichier a été téléchargé
    if (!isset($_FILES['document_file']) || $_FILES['document_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => "Fichier invalide"]);
        exit;
    }

    // Récupérer le nom du type pour créer le bon dossier
    $stmt = $pdo->prepare("SELECT name FROM types WHERE id = :type_id");
    $stmt->execute([':type_id' => $typeId]);
    $type = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$type) {
        echo json_encode(['success' => false, 'message' => "Type de document invalide"]);
        exit;
    }

    // Créer les dossiers correspondants s'ils n'existent pas
    $typeFolder = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($type['name']));
    $uploadDir = "../../uploads/documents/$typeFolder/";
    $viewableDir = "../../uploads/viewable/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (!is_dir($viewableDir)) {
        mkdir($viewableDir, 0777, true);
    }

    // Stocker le fichier PDF dans le dossier de type
    $fileName = time() . "_" . basename($_FILES["document_file"]["name"]);
    $filePath = $uploadDir . $fileName;
    $viewablePath = $viewableDir . $fileName;

    if (!move_uploaded_file($_FILES["document_file"]["tmp_name"], $filePath)) {
        echo json_encode(['success' => false, 'message' => "Échec de l'enregistrement du fichier"]);
        exit;
    }

    // Créer une copie dans le dossier viewable
    if (!copy($filePath, $viewablePath)) {
        echo json_encode(['success' => false, 'message' => "Échec de la création de la copie"]);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Insérer le document dans la BDD avec le chemin mis à jour
        $stmt = $pdo->prepare("INSERT INTO documents (type_id, document_path) VALUES (:type_id, :file_path)");
        $stmt->execute([
            ':type_id' => $typeId,
            ':file_path' => "documents/$typeFolder/$fileName"
        ]);
        $documentId = $pdo->lastInsertId();

        // Insérer les champs dynamiques
        $stmt = $pdo->prepare("INSERT INTO document_fields (document_id, field_id, field_value) VALUES (:document_id, :field_id, :field_value)");
        foreach ($fields as $fieldId => $fieldValue) {
            $stmt->execute([
                ':document_id' => $documentId,
                ':field_id' => $fieldId,
                ':field_value' => $fieldValue
            ]);
        }
        
        $admin_id = $_SESSION['admin_id']; 
        if ($admin_id) {
            $action = "Document créé: $fileName";
            $sql = "INSERT INTO admin_logs (admin_id, actions, created_at) VALUES (:admin_id, :actions, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':admin_id' => $admin_id,
                ':actions' => $action
            ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => "Document ajouté avec succès"]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => "Erreur : " . $e->getMessage()]);
    }
}
