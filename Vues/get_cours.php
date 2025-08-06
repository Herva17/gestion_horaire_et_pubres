<?php
require_once '../Models/Config.php';
require_once '../Models/MaClasse.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $manager = new Horaire($pdo);

    if (!isset($_GET['id'])) {
        throw new Exception("ID du cours manquant");
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null) {
        throw new Exception("ID du cours invalide");
    }

    $cours = $manager->getCoursById($id);
    if (!$cours) {
        throw new Exception("Cours non trouvé");
    }

    echo json_encode(['success' => true, 'data' => $cours]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}