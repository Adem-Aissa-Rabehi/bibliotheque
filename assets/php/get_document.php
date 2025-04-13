<?php
require_once 'database.php';

$typeId = isset($_GET['type']) && $_GET['type'] !== "" ? $_GET['type'] : null;
$filters = array_filter($_GET, fn($key) => str_starts_with($key, 'field_'), ARRAY_FILTER_USE_KEY);

$pdo = getDatabaseConnection();

$query = "SELECT documents.id,  documents.publication_date, documents.document_path, types.name AS type 
          FROM documents 
          JOIN types ON documents.type_id = types.id";

if ($typeId || !empty($filters)) {
    $query .= " WHERE ";
    $conditions = [];

    if ($typeId) {
        $conditions[] = "documents.type_id = :typeId";
    }

    foreach ($filters as $key => $value) {
        $fieldId = str_replace("field_", "", $key);
        $conditions[] = "documents.id IN (SELECT document_id FROM document_fields WHERE field_id = :$key AND field_value = :value_$key)";
    }

    $query .= implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($query);

if ($typeId) $stmt->bindParam(':typeId', $typeId);
foreach ($filters as $key => $value) {
    $fieldId = str_replace("field_", "", $key);
    $stmt->bindParam(":$key", $fieldId);
    $stmt->bindParam(":value_$key", $value);
}

$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
