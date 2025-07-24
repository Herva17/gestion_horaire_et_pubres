<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    if (!isset($_GET['id'])) {
        throw new Exception("ID de promotion non spécifié");
    }
    
    $promotionId = (int)$_GET['id'];
    $promotion = $horaire->getPromotionById($promotionId);
    
    if (!$promotion) {
        throw new Exception("Promotion non trouvée");
    }
    
    echo json_encode($promotion);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>