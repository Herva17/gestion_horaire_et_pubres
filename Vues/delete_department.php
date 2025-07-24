<?php
session_start();
require_once __DIR__ . '/../Models/MaClasse.php';
require_once __DIR__ . '/../Models/Config.php';

header('Content-Type: application/json');

try {
    // 1. Vérification de la méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception("Méthode non autorisée", 405);
    }

    // 2. Validation de l'ID
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("ID invalide", 400);
    }

    $id = (int)$_GET['id'];
    $pdo = getPDO();
    $horaire = new Horaire($pdo);

    // 3. Vérification que l'ID correspond bien à un département
    $departement = $horaire->getDepartementById($id);
    if (!$departement) {
        throw new Exception("Aucun département trouvé avec cet ID", 404);
    }

    // 4. Vérification des sections dépendantes
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM section WHERE id_departement = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("Impossible de supprimer : des sections dépendent de ce département", 400);
    }

    // 5. Suppression effective
    if ($horaire->deleteDepartement($id)) {
        echo json_encode([
            'success' => true,
            'message' => 'Département supprimé avec succès'
        ]);
    } else {
        throw new Exception("Échec de la suppression", 500);
    }

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>