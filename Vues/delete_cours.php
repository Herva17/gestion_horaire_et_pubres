<?php
require_once '../Models/Config.php';
require_once '../Models/MaClasse.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $manager = new Horaire($pdo);

    // Vérification CSRF token
    if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_COOKIE['csrf_token']) {
        throw new Exception("Token CSRF invalide");
    }

    if (!isset($_POST['id'])) {
        throw new Exception("ID du cours manquant");
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null) {
        throw new Exception("ID du cours invalide");
    }

    $success = $manager->deleteCours($id);
    if (!$success) {
        throw new Exception("Erreur lors de la suppression du cours");
    }

    echo json_encode(['success' => true, 'message' => 'Cours supprimé avec succès']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}