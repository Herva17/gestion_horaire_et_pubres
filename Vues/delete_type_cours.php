<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Vérification de la méthode HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée', 405);
    }

    // Vérification du token CSRF
    if (!isset($_SERVER['HTTP_COOKIE'])) {
        throw new Exception('Token CSRF manquant', 403);
    }
    
    parse_str($_SERVER['HTTP_COOKIE'], $cookies);
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($cookies['csrf_token'] ?? '')) {
        throw new Exception('Token CSRF invalide', 403);
    }

    // Validation de l'ID
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id || $id <= 0) {
        throw new Exception('ID invalide', 400);
    }

    // Connexion à la base de données
    $pdo = getPDO();
    $typeCoursManager = new Horaire($pdo);

    // Tentative de suppression
    $success = $typeCoursManager->deleteTypeCours($id);

    if (!$success) {
        throw new Exception('Échec de la suppression', 500);
    }

    // Message de succès
    $_SESSION['flash'] = [
        'type' => 'success',
        'message' => 'Le type de cours a été supprimé avec succès'
    ];

    // Réponse JSON
    echo json_encode([
        'success' => true,
        'message' => 'Suppression réussie',
        'id' => $id
    ]);

} catch (PDOException $e) {
    // Erreur de base de données
    error_log('Erreur PDO: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données'
    ]);
    
} catch (Exception $e) {
    // Autres erreurs
    $statusCode = $e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 500;
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}