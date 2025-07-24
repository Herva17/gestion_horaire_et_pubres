<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    if (!isset($_GET['enseignant_id'])) {
        throw new Exception('ID enseignant manquant');
    }
    
    $enseignantId = (int)$_GET['enseignant_id'];
    $disponibilites = $horaire->getDisponibilitesByEnseignant($enseignantId);
    
    echo json_encode($disponibilites);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}