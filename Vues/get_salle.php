<?php
require_once '../Models/Config.php';
require_once '../Models/MaClasse.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $manager = new Horaire($pdo);

    if (!isset($_GET['id'])) {
        throw new Exception("ID de la salle manquant");
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null) {
        throw new Exception("ID de la salle invalide");
    }

    $salle = $manager->getSalleById($id);
    if (!$salle) {
        throw new Exception("Salle non trouvée");
    }

    echo json_encode(['success' => true, 'data' => $salle]);
} catch (Exception $e) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}