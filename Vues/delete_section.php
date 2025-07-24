<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    // Validation
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' || !isset($_GET['id'])) {
        throw new Exception('Méthode non autorisée', 405);
    }

    $id = (int)$_GET['id'];
    if ($id <= 0) {
        throw new Exception('ID invalide', 400);
    }

    $pdo = getPDO();
    $horaire = new Horaire($pdo);

    // Vérifier l'existence avant suppression
    $section = $horaire->getSectionById($id);
    if (!$section) {
        throw new Exception('Section non trouvée', 404);
    }

    // Vérifier les dépendances
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM promotion WHERE id_section = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('Impossible de supprimer: des promotions sont liées', 400);
    }

    // Suppression
    if ($horaire->deleteSection($id)) {
        echo json_encode([
            'success' => true,
            'message' => 'Section supprimée avec succès'
        ]);
    } else {
        throw new Exception('Échec de la suppression', 500);
    }

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>