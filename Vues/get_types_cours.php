<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    $id = $_GET['id'] ?? 0;
    if (!$id) {
        throw new Exception('ID manquant', 400);
    }

    $typeCours = $horaire->getTypeCoursById($id);
    if (!$typeCours) {
        throw new Exception('Type de cours non trouvé', 404);
    }

    echo json_encode([
        'success' => true,
        'data' => $typeCours
    ]);
} catch (Exception $e) {
    http_response_code($e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}