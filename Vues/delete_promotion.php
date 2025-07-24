<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception("Méthode non autorisée");
    }
    
    if (!isset($_GET['id'])) {
        throw new Exception("ID de promotion non spécifié");
    }
    
    $promotionId = (int)$_GET['id'];
    $success = $horaire->deletePromotion($promotionId);
    
    if (!$success) {
        throw new Exception("Échec de la suppression de la promotion");
    }
    
    $_SESSION['flash'] = [
        'type' => 'success',
        'message' => 'Promotion supprimée avec succès'
    ];
    
    echo json_encode(['success' => true, 'message' => 'Promotion supprimée avec succès']);
} catch (Exception $e) {
    http_response_code(500);
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => $e->getMessage()
    ];
    echo json_encode(['error' => $e->getMessage()]);
}
?>