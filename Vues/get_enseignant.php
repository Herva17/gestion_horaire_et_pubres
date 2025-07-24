<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    if (!isset($_GET['id'])) {
        throw new Exception('ID manquant');
    }
    
    $enseignant = $horaire->getEnseignantById($_GET['id']);
    
    if (!$enseignant) {
        throw new Exception('Enseignant non trouvé');
    }
    
    echo json_encode($enseignant);
} catch (Exception $e) {
    http_response_code(404);
    echo json_encode(['error' => $e->getMessage()]);
}