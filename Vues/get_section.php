<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

if (isset($_GET['id'])) {
    try {
        $pdo = getPDO();
        $horaire = new Horaire($pdo);
        $section = $horaire->getSectionById($_GET['id']);
        
        if ($section) {
            header('Content-Type: application/json');
            echo json_encode($section);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Section non trouvée']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID manquant']);
}
?>