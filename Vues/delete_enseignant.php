<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception('Méthode non autorisée');
    }
    
    if (!isset($_GET['id'])) {
        throw new Exception('ID manquant');
    }
    
    $success = $horaire->deleteEnseignant($_GET['id']);
    
    if (!$success) {
        throw new Exception('Échec de la suppression');
    }
    
    echo json_encode(['success' => true, 'message' => 'Enseignant supprimé avec succès']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}